# MyWeeklyAllowance

[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net/)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-10.5-green)](https://phpunit.de/)
[![Tests](https://img.shields.io/badge/Tests-25%20passing-success)](https://github.com/Wissem95/MyWeeklyAllowance)
[![TDD](https://img.shields.io/badge/Methodology-TDD-orange)](https://en.wikipedia.org/wiki/Test-driven_development)

Application de gestion d'argent de poche pour adolescents, développée selon la méthode **TDD (Test Driven Development)**.

## 📋 Fonctionnalités

- **Création de compte** : Créer un compte pour un adolescent (10-17 ans)
- **Dépôt d'argent** : Les parents peuvent déposer de l'argent
- **Enregistrement des dépenses** : Suivi des dépenses avec description et historique
- **Allocation hebdomadaire** : Configuration d'un versement automatique hebdomadaire

## 🛠 Installation

```bash
# Cloner le repository
git clone https://github.com/Wissem95/MyWeeklyAllowance.git
cd MyWeeklyAllowance

# Installer les dépendances
composer install
```

## 🧪 Exécution des tests

```bash
# Lancer tous les tests
./vendor/bin/phpunit

# Avec sortie détaillée
./vendor/bin/phpunit --testdox

# Avec couverture de code (nécessite Xdebug ou PCOV)
./vendor/bin/phpunit --coverage-text
./vendor/bin/phpunit --coverage-html coverage-report
```

## 📊 Structure du projet

```
MyWeeklyAllowance/
├── src/
│   └── AllowanceAccount.php    # Classe principale
├── tests/
│   └── AllowanceAccountTest.php # Tests unitaires (25 tests)
├── vendor/                      # Dépendances Composer
├── composer.json                # Configuration Composer
├── phpunit.xml                  # Configuration PHPUnit
└── README.md                    # Documentation
```

## 💡 Exemple d'utilisation

```php
<?php

require 'vendor/autoload.php';

use App\AllowanceAccount;

// Créer un compte pour Lucas, 15 ans, avec 50€ de départ
$account = new AllowanceAccount("Lucas", 15, 50.0);

// Configurer une allocation hebdomadaire de 20€
$account->setWeeklyAllowance(20.0);

// Déposer de l'argent
$account->deposit(30.0);

// Enregistrer une dépense
if ($account->spend(25.0, "Jeu vidéo")) {
    echo "Dépense enregistrée !";
}

// Consulter le solde
echo "Solde actuel : " . $account->getBalance() . "€";

// Appliquer l'allocation hebdomadaire
$account->applyWeeklyAllowance();

// Consulter l'historique des transactions
$history = $account->getTransactionHistory();
```

## 🔍 Validations implémentées

### Création de compte
- ✅ Le nom ne peut pas être vide
- ✅ L'âge doit être entre 10 et 17 ans
- ✅ Le solde initial ne peut pas être négatif

### Dépôts
- ✅ Le montant doit être supérieur à zéro

### Dépenses
- ✅ Le montant doit être supérieur à zéro
- ✅ La description est obligatoire
- ✅ Vérification du solde disponible (retourne `false` si insuffisant)

### Allocation hebdomadaire
- ✅ Ne peut pas être négative

## 📈 Tests et couverture

- **25 tests unitaires** avec **43 assertions**
- Tests de création de compte (6 tests)
- Tests de dépôt d'argent (4 tests)
- Tests d'enregistrement des dépenses (7 tests)
- Tests d'allocation hebdomadaire (5 tests)
- Tests de cas limites (3 tests)

## 🎯 Méthodologie TDD appliquée

Ce projet a été développé en suivant strictement la méthodologie TDD (Test-Driven Development) :

1. **🔴 RED** : Écriture de tous les tests unitaires (qui échouent initialement)
2. **🟢 GREEN** : Implémentation du code pour faire passer tous les tests
3. **🔵 REFACTOR** : Amélioration du code et documentation

### Historique des commits

```
56bb468 RED: Ajout des tests unitaires - Phase TDD
c76eb66 GREEN: Implémentation AllowanceAccount - Tous les tests passent
```

## 🤝 Contribution

Ce projet est un exercice pédagogique démontrant la méthode TDD.

## 📝 Licence

Projet éducatif - HETIC

## 👨‍💻 Auteur

Wissem - [GitHub](https://github.com/Wissem95)
