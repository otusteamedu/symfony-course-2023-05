<?php

namespace App\Controller\Api\CreateUser\v5;

use App\Controller\Api\CreateUser\v5\Input\CreateUserDTO;
use App\Controller\Api\CreateUser\v5\Output\UserIsCreatedDTO;
use App\Controller\Common\ErrorResponseTrait;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class CreateUserAction extends AbstractFOSRestController
{
    use ErrorResponseTrait;

    public function __construct(private readonly CreateUserManagerInterface $saveUserManager)
    {
    }

    #[Rest\Post(path: '/api/v5/users')]
    /**
     * @OA\Post(
     *     operationId="addUser",
     *     tags={"Пользователи"},
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\JsonContent(ref=@Model(type=CreateUserDTO::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref=@Model(type=UserIsCreatedDTO::class))
     *     )
     * )
     */
    public function saveUserAction(#[MapRequestPayload] CreateUserDTO $request): Response
    {
        $user = $this->saveUserManager->saveUser($request);
        [$data, $code] = ($user->id === null) ? [['success' => false], 400] : [['user' => $user], 200];

        return $this->handleView($this->view($data, $code));
    }
}
