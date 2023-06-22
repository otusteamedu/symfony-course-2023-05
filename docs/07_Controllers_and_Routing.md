# Контроллеры и маршрутизация

Запускаем контейнеры командой `docker-compose up -d`

## Добавляем UserController

1. Создаём класс `App\Repository\UserRepository`
   ```php
    <?php
    
    namespace App\Repository;
    
    use App\Entity\User;
    use Doctrine\ORM\EntityRepository;
    
    class UserRepository extends EntityRepository
    {
        /**
         * @return User[]
         */
        public function getUsers(int $page, int $perPage): array
        {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('t')
                ->from($this->getClassName(), 't')
                ->orderBy('t.id', 'DESC')
                ->setFirstResult($perPage * $page)
                ->setMaxResults($perPage);
    
            return $qb->getQuery()->getResult();
        }
    }
    ```
2. Исправляем в классе `App\Entity\User` атрибут класса `ORM\Entity`
    ```php
    #[ORM\Entity(repositoryClass: UserRepository::class)]
    ```
3. Исправляем класс `App\Manager\UserManager`
    ```php
    <?php
    
    namespace App\Manager;
    
    use App\Entity\User;
    use App\Repository\UserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    
    class UserManager
    {
        private EntityManagerInterface $entityManager;
    
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
    
        public function saveUser(string $login): ?int
        {
            $user = new User();
            $user->setLogin($login);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    
            return $user->getId();
        }
    
        public function updateUser(int $userId, string $login): bool
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->find($userId);
            if ($user === null) {
                return false;
            }
            $user->setLogin($login);
            $this->entityManager->flush();
    
            return true;
        }
    
        public function deleteUser(int $userId): bool
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->find($userId);
            if ($user === null) {
                return false;
            }
            $this->entityManager->remove($user);
            $this->entityManager->flush();
    
            return true;
        }
    
        /**
         * @return User[]
         */
        public function getUsers(int $page, int $perPage): array
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
    
            return $userRepository->getUsers($page, $perPage);
        }
    }
    ```
4. Создаём класс `App\Controller\Api\v1\UserController`
    ```php
    <?php
    
    namespace App\Controller\Api\v1;
    
    use App\Manager\UserManager;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    
    #[Route(path: '/api/v1/user')]
    class UserController extends AbstractController
    {
        private const DEFAULT_PAGE = 0;
        private const DEFAULT_PER_PAGE = 20;
        
        public function __construct(private readonly UserManager $userManager)
        {
        }
    
        #[Route(path: '', methods: ['POST'])]
        public function saveUserAction(Request $request): Response
        {
            $login = $request->request->get('login');
            $userId = $this->userManager->saveUser($login);
            [$data, $code] = $userId === null ?
                [['success' => false], Response::HTTP_BAD_REQUEST] :
                [['success' => true, 'userId' => $userId], Response::HTTP_OK];
    
            return new JsonResponse($data, $code);
        }
    
        #[Route(path: '', methods: ['GET'])]
        public function getUsersAction(Request $request): Response
        {
            $perPage = $request->query->get('perPage');
            $page = $request->query->get('page');
            $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
            $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
    
            return new JsonResponse(['users' => $users], $code);
        }
    
        #[Route(path: '', methods: ['DELETE'])]
        public function deleteUserAction(Request $request): Response
        {
            $userId = $request->query->get('userId');
            $result = $this->userManager->deleteUser($userId);
    
            return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
    
        #[Route(path: '', methods: ['PATCH'])]
        public function updateUserAction(Request $request): Response
        {
            $userId = $request->request->get('userId');
            $login = $request->request->get('login');
            $result = $this->userManager->updateUser($userId, $login);
    
            return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
    }
    ```
5. Заходим в контейнер `php` командой `docker exec -it php sh`. Дальнейшие команды выполняются из контейнера
6. Выполняем команду `php bin/console debug:router`, видим список наших endpoint'ов из контроллера
7. Выполняем запрос Add user из Postman-коллекции, видим, что пользователь добавился
8. Выполняем запрос Delete user из Postman-коллекции с id из результата предыдущего запроса, видим, что пользователь
   удалился

## Добавляем инъекцию id в `UserController::deleteUserByIdAction`

1. В классе `App\Controller\Api\v1\UserController` добавляем новый метод `deleteUserByIdAction`
    ```php
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteUserByIdAction(int $id): Response
    {
        $result = $this->userManager->deleteUser($id);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
    ```
2. Ещё раз выполняем запрос Add user из Postman-коллекции, чтобы создать пользователя
3. Выполняем запрос Delete user by id из Postman-коллекции с id из результата предыдущего запроса, видим, что
   пользователь удалился

## Исправляем запрос Patch user

1. Ещё раз выполняем запрос Add user из Postman-коллекции, чтобы создать пользователя
2. Пробуем отправить запрос Patch user из Postman-коллекции для созданного в предыдущем запросе пользователя, видим
   ошибку 500
3. Переносим в Postman-коллекции в запросе Patch user параметры из тела в строку запроса
4. Исправляем в классе `App\Controller\Api\v1\UserController` метод `updateUserAction`
    ```php
    #[Route(path: '', methods: ['PATCH'])]
    public function updateUserAction(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $login = $request->query->get('login');
        $result = $this->userManager->updateUser($userId, $login);

        return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }
    ```
5. Ещё раз пробуем отправить запрос Patch user из Postman-коллекции, логин обновляется

## Исправляем запрос Get user list

1. Отправляем запрос Get user list из Postman-коллекции, видим список пустых объектов
2. Исправляем в классе `App\Controller\Api\v1\UserController` метод `getUsersAction`
    ```php
    #[Route(path: '', methods: ['GET'])]
    public function getUsersAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
        $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
    }
    ```
3. Ещё раз отправляем запрос Get user list из Postman-коллекции, видим список пользователей с данными

## Используем атрибуты из SensioFrameworkExtraBundle

1. Устанавливаем пакет `sensio/framework-extra-bundle` командой `composer require sensio/framework-extra-bundle`
2. Устанавливаем пакет `symfony/expression-language` командой `composer require symfony/expression-language`
   (понадобится для использования атрибута `#Entity`)
3. Создаём класс `App\Controller\Api\v2\UserController`
    ```php
    <?php
    
    namespace App\Controller\Api\v2;
    
    use App\Entity\User;
    use App\Manager\UserManager;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    
    #[Route(path: 'api/v2/user')]
    class UserController extends AbstractController
    {
        private const DEFAULT_PAGE = 0;
        private const DEFAULT_PER_PAGE = 20;
    
        public function __construct(private readonly UserManager $userManager)
        {
        }
    
        #[Route(path: '', methods: ['POST'])]
        public function saveUserAction(Request $request): Response
        {
            $login = $request->request->get('login');
            $userId = $this->userManager->saveUser($login);
            [$data, $code] = $userId === null ?
                [['success' => false], Response::HTTP_BAD_REQUEST] :
                [['success' => true, 'userId' => $userId], Response::HTTP_OK];
    
            return new JsonResponse($data, $code);
        }
    
        #[Route(path: '', methods: ['GET'])]
        public function getUsersAction(Request $request): Response
        {
            $perPage = $request->request->get('perPage');
            $page = $request->request->get('page');
            $users = $this->userManager->getUsers($page ?? self::DEFAULT_PAGE, $perPage ?? self::DEFAULT_PER_PAGE);
            $code = empty($users) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
    
            return new JsonResponse(['users' => array_map(static fn(User $user) => $user->toArray(), $users)], $code);
        }
    
        #[Route(path: '/by-login/{user_login}', methods: ['GET'], priority: 2)]
        #[ParamConverter('user', options: ['mapping' => ['user_login' => 'login']])]
        public function getUserByLoginAction(User $user): Response
        {
            return new JsonResponse(['user' => $user->toArray()], Response::HTTP_OK);
        }
    
        #[Route(path: '/{user_id}', requirements: ['user_id' => '\d+'], methods: ['DELETE'])]
        #[Entity('user', expr: 'repository.find(user_id)')]
        public function deleteUserAction(User $user): Response
        {
            $result = $this->userManager->deleteUser($user);
    
            return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
    
        #[Route(path: '', methods: ['PATCH'])]
        public function updateUserAction(Request $request): Response
        {
            $userId = $request->request->get('userId');
            $login = $request->request->get('login');
            $result = $this->userManager->updateUser($userId, $login);
            [$data, $code] = $result ? [null, Response::HTTP_NOT_FOUND] : [['user' => $result->toArray()], Response::HTTP_OK];
    
            return new JsonResponse($data, $code);
        }
    }
     ```
4. Исправляем класс `App\Manager\UserManager`
    1. Переименовываем метод `deletUser` в `deleteUserById`
    2. Добавляем новый метод `deleteUser`
        ```php
        public function deleteUser(User $user): bool
        {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
   
            return true;
        }
        ```
    3. Исправляем метод `deleteUserById`
        ```php
        public function deleteUserById(int $userId): bool
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->find($userId);
            if ($user === null) {
                return false;
            }
            return $this->deleteUser($user);
        }
        ```
    4. Исправляем метод `updateUser`
        ```php
        public function updateUser(int $userId, string $login): ?User
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->entityManager->getRepository(User::class);
            /** @var User $user */
            $user = $userRepository->find($userId);
            if ($user === null) {
                return null;
            }
            $user->setLogin($login);
            $this->entityManager->flush();
    
            return $user;
        }
        ```
5. В классе `App\Controller\Api\v1\UserController`
    1. Исправляем метод `deleteUserAction`
        ```php
        #[Route(path: '', methods: ['DELETE'])]
        public function deleteUserAction(Request $request): Response
        {
            $userId = $request->query->get('userId');
            $result = $this->userManager->deleteUserById($userId);

            return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
        ```
    2. Исправляем метод `updateUserAction`
        ```php
        #[Route(path: '', methods: ['PATCH'])]
        public function updateUserAction(Request $request): Response
        {
            $userId = $request->query->get('userId');
            $login = $request->query->get('login');
            $result = $this->userManager->updateUser($userId, $login);

            return new JsonResponse(['success' => $result !== null], ($result !== null) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
        ```
    3. Исправляем метод `deleteUserByIdAction`
        ```php
        #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
        public function deleteUserByIdAction(int $id): Response
        {
             $result = $this->userManager->deleteUserById($id);

              return new JsonResponse(['success' => $result], $result ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
        }
        ```
7. Выполняем запрос Add user v2 из Postman-коллекции, чтобы создать пользователя
8. Выполняем запрос Delete user v2 из Postman-коллекции с id из результата предыдущего запроса
9. Выполняем запрос Get user by login v2 из Postman-коллекции, видим, что пользователь находится по логину
