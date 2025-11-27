<?php

namespace App;

class AllowanceAccount
{
    private string $name;
    private int $age;
    private float $balance;
    private float $weeklyAllowance;
    private array $transactionHistory;

    public function __construct(string $name, int $age, float $initialBalance = 0.0)
    {
        if (empty(trim($name))) {
            throw new \InvalidArgumentException("Le nom ne peut pas être vide");
        }

        if ($age < 10 || $age > 17) {
            throw new \InvalidArgumentException("L'âge doit être compris entre 10 et 17 ans");
        }

        if ($initialBalance < 0) {
            throw new \InvalidArgumentException("Le solde initial ne peut pas être négatif");
        }

        $this->name = $name;
        $this->age = $age;
        $this->balance = $initialBalance;
        $this->weeklyAllowance = 0.0;
        $this->transactionHistory = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getBalance(): float
    {
        return round($this->balance, 2);
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Le montant du dépôt doit être supérieur à zéro");
        }

        $this->balance += $amount;
    }

    public function spend(float $amount, string $description): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Le montant de la dépense doit être supérieur à zéro");
        }

        if (empty(trim($description))) {
            throw new \InvalidArgumentException("La description de la dépense est obligatoire");
        }

        if ($amount > $this->balance) {
            return false;
        }

        $this->balance -= $amount;
        $this->transactionHistory[] = [
            'amount' => $amount,
            'description' => $description,
            'date' => new \DateTime()
        ];

        return true;
    }

    public function getTransactionHistory(): array
    {
        return $this->transactionHistory;
    }

    public function setWeeklyAllowance(float $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("L'allocation hebdomadaire ne peut pas être négative");
        }

        $this->weeklyAllowance = $amount;
    }

    public function getWeeklyAllowance(): float
    {
        return $this->weeklyAllowance;
    }

    public function applyWeeklyAllowance(): void
    {
        $this->balance += $this->weeklyAllowance;
    }
}
