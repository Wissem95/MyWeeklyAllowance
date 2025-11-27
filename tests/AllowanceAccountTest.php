<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\AllowanceAccount;

class AllowanceAccountTest extends TestCase
{
    // ========================================
    // TESTS DE CRÉATION DE COMPTE
    // ========================================

    public function testCreateAccountWithValidData(): void
    {
        $account = new AllowanceAccount("Lucas", 15);

        $this->assertInstanceOf(AllowanceAccount::class, $account);
        $this->assertEquals("Lucas", $account->getName());
        $this->assertEquals(15, $account->getAge());
        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testCreateAccountWithInitialBalance(): void
    {
        $account = new AllowanceAccount("Emma", 14, 50.0);

        $this->assertEquals(50.0, $account->getBalance());
    }

    public function testCreateAccountThrowsExceptionForInvalidAge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'âge doit être compris entre 10 et 17 ans");

        new AllowanceAccount("Test", 8);
    }

    public function testCreateAccountThrowsExceptionForAdult(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new AllowanceAccount("Test", 18);
    }

    public function testCreateAccountThrowsExceptionForEmptyName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le nom ne peut pas être vide");

        new AllowanceAccount("", 15);
    }

    public function testCreateAccountThrowsExceptionForNegativeInitialBalance(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le solde initial ne peut pas être négatif");

        new AllowanceAccount("Test", 15, -10.0);
    }

    // ========================================
    // TESTS DE DÉPÔT D'ARGENT
    // ========================================

    public function testDepositValidAmount(): void
    {
        $account = new AllowanceAccount("Lucas", 15);
        $account->deposit(25.50);

        $this->assertEquals(25.50, $account->getBalance());
    }

    public function testDepositMultipleTimes(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 10.0);
        $account->deposit(20.0);
        $account->deposit(15.0);

        $this->assertEquals(45.0, $account->getBalance());
    }

    public function testDepositThrowsExceptionForZeroAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant du dépôt doit être supérieur à zéro");

        $account = new AllowanceAccount("Lucas", 15);
        $account->deposit(0);
    }

    public function testDepositThrowsExceptionForNegativeAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant du dépôt doit être supérieur à zéro");

        $account = new AllowanceAccount("Lucas", 15);
        $account->deposit(-10.0);
    }

    // ========================================
    // TESTS D'ENREGISTREMENT DE DÉPENSES
    // ========================================

    public function testSpendValidAmount(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $result = $account->spend(30.0, "Jeu vidéo");

        $this->assertTrue($result);
        $this->assertEquals(70.0, $account->getBalance());
    }

    public function testSpendEntireBalance(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 50.0);
        $result = $account->spend(50.0, "Achat");

        $this->assertTrue($result);
        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testSpendReturnsFalseWhenInsufficientFunds(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 20.0);
        $result = $account->spend(50.0, "Trop cher");

        $this->assertFalse($result);
        $this->assertEquals(20.0, $account->getBalance());
    }

    public function testSpendThrowsExceptionForZeroAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant de la dépense doit être supérieur à zéro");

        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $account->spend(0, "Test");
    }

    public function testSpendThrowsExceptionForNegativeAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant de la dépense doit être supérieur à zéro");

        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $account->spend(-10.0, "Test");
    }

    public function testSpendThrowsExceptionForEmptyDescription(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("La description de la dépense est obligatoire");

        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $account->spend(10.0, "");
    }

    public function testGetTransactionHistory(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $account->spend(20.0, "Livre");
        $account->spend(15.0, "Goûter");

        $history = $account->getTransactionHistory();

        $this->assertCount(2, $history);
        $this->assertEquals("Livre", $history[0]['description']);
        $this->assertEquals(20.0, $history[0]['amount']);
        $this->assertEquals("Goûter", $history[1]['description']);
    }

    // ========================================
    // TESTS D'ALLOCATION HEBDOMADAIRE
    // ========================================

    public function testSetWeeklyAllowance(): void
    {
        $account = new AllowanceAccount("Lucas", 15);
        $account->setWeeklyAllowance(20.0);

        $this->assertEquals(20.0, $account->getWeeklyAllowance());
    }

    public function testSetWeeklyAllowanceThrowsExceptionForNegativeAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation hebdomadaire ne peut pas être négative");

        $account = new AllowanceAccount("Lucas", 15);
        $account->setWeeklyAllowance(-5.0);
    }

    public function testApplyWeeklyAllowance(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 10.0);
        $account->setWeeklyAllowance(25.0);
        $account->applyWeeklyAllowance();

        $this->assertEquals(35.0, $account->getBalance());
    }

    public function testApplyWeeklyAllowanceWithZeroAllowance(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 10.0);
        $account->applyWeeklyAllowance();

        $this->assertEquals(10.0, $account->getBalance());
    }

    public function testApplyWeeklyAllowanceMultipleTimes(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 0.0);
        $account->setWeeklyAllowance(15.0);
        $account->applyWeeklyAllowance();
        $account->applyWeeklyAllowance();
        $account->applyWeeklyAllowance();

        $this->assertEquals(45.0, $account->getBalance());
    }

    // ========================================
    // TESTS SUPPLÉMENTAIRES (EDGE CASES)
    // ========================================

    public function testBalancePrecision(): void
    {
        $account = new AllowanceAccount("Lucas", 15, 100.0);
        $account->spend(33.33, "Achat 1");
        $account->spend(33.33, "Achat 2");
        $account->spend(33.34, "Achat 3");

        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testAccountWithMinimumAge(): void
    {
        $account = new AllowanceAccount("Jeune", 10);

        $this->assertEquals(10, $account->getAge());
    }

    public function testAccountWithMaximumAge(): void
    {
        $account = new AllowanceAccount("Ado", 17);

        $this->assertEquals(17, $account->getAge());
    }
}
