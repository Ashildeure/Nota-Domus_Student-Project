<?php

class ExampleClass {
    private int $id;
    private string $name;

    public function __construct(int $id, string $name) {
        $this->setId($id);
        $this->setName($name);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }
}

/**
 * Renvoie le message d'erreur complet en prenant en compte le message générique.
 * Ce message concerne les erreurs survenant en dehors du contexte des tests.
 * @param string $message
 * @return string
 */
function getFullExternalMessage(string $message): string {
    return EXTERNAL_MESSAGE . "\nMessage d'erreur complet : \n\n" . $message;
}
const EXTERNAL_MESSAGE = "Exception en dehors du contexte des tests.";