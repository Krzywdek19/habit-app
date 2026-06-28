<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HabitController extends AbstractController
{

    #[Route('/api/v1/habits/{id}',name: 'api_habits_show', methods: ['GET',])]
    public function getHabitById(int $id) : JsonResponse
    {
        $dummyObject = [
            'id' => $id,
            'title' => 'Habit',
            'completed' => false
        ];

        return $this->json($dummyObject);
    }
}
