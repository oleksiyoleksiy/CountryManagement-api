<?php

namespace App\DTO;

class MessageDTO
{
    public function __construct(private string $content) {
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content
        ];
    }
}
