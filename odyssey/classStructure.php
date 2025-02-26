<?php

// Абстрактный класс для банковских счетов
abstract class BankAccount {
    const STATUS_ACTIVE = 'active'; // Статус счета - активный
    const STATUS_INACTIVE = 'inactive'; // Статус счета - неактивный
    const STATUS_CLOSED = 'closed'; // Статус счета - закрытый

    protected string $accountNumber; // Номер счета
    protected ?DateTimeImmutable $accountDate = null; // Дата открытия счета
    protected string $bankName; // Название банка
    protected float $balance = 0.0; // Баланс счета
    protected string $currency; // Валюта счета (например, "RUB", "USD", "EUR")
    protected ?string $bic = null; // Код BIC банка
    protected ?string $iban = null; // Код IBAN (международный номер счета)
    protected ?string $swift = null; // Код SWIFT (международный код для платежей)
    protected ?string $ownerName = null; // Владелец счета
    protected string $status = self::STATUS_ACTIVE; // Статус счета

    // Метод для проверки корректности статуса счета
    private function isValidStatus(string $status): bool {
        // Проверка на допустимые статусы счета
        return in_array($status, [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_CLOSED], true);
    }

    // Конструктор класса
    public function __construct(string $accountNumber, string $bankName, string $currency) {
        $this->accountNumber = $accountNumber;
        $this->bankName = $bankName;
        $this->currency = $currency;
        $this->accountDate = new DateTimeImmutable();
    }

    // Установка даты открытия счета
    public function setAccountDate(DateTimeImmutable $date): void {
        $this->accountDate = $date;
    }

    // Установка статуса счета
    public function setStatus(string $status): void {
        if (!$this->isValidStatus($status)) {
            throw new InvalidArgumentException("Недопустимый статус счета: $status");
        }
        $this->status = $status;
    }

    // Установка BIC банка
    public function setBic(string $bic): void {
        if (!preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $bic)) {
            throw new InvalidArgumentException("Некорректный BIC: $bic");
        }
        $this->bic = $bic;
    }

    // Установка IBAN
    public function setIban(string $iban): void {
        if (!preg_match('/^[A-Z]{2}[0-9A-Z]{13,32}$/', $iban)) {
            throw new InvalidArgumentException("Некорректный IBAN: $iban");
        }
        $this->iban = $iban;
    }

    // Установка SWIFT
    public function setSwift(string $swift): void {
        if (!preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $swift)) {
            throw new InvalidArgumentException("Некорректный SWIFT: $swift");
        }
        $this->swift = $swift;
    }

    // Установка владельца счета
    public function setOwnerName(string $ownerName): void {
        $this->ownerName = $ownerName;
    }

    // Установка баланса счета
    public function setBalance(float $balance): void {
        if ($balance < 0) {
            throw new InvalidArgumentException("Недопустимый баланс счета: $balance");
        }
        $this->balance = $balance;
    }


    // Получение номера счета
    public function getAccountNumber(): string {
        return $this->accountNumber;
    }

    // Получение названия банка
    public function getBankName(): string {
        return $this->bankName;
    }

    // Получение баланса счета
    public function getBalance(): float {
        return $this->balance;
    }

    // Получение валюты счета
    public function getCurrency(): string {
        return $this->currency;
    }

    // Получение BIC
    public function getBic(): ?string {
        return $this->bic;
    }

    // Получение статуса счета
    public function getStatus(): string {
        return $this->status;
    }

    // Получение IBAN
    public function getIban(): ?string {
        return $this->iban;
    }

    // Получение SWIFT
    public function getSwift(): ?string {
        return $this->swift;
    }

    // Получение владельца счета
    public function getOwnerName(): ?string {
        return $this->ownerName;
    }

    // Метод для получения даты открытия счета в заданном формате
    public function getAccountDate(string $format = 'd-m-Y'): ?string {
        return $this->accountDate?->format($format) ?? null;
    }
}


// Класс для банковских счетов поставщиков
class SupplierBankAccount extends BankAccount {
    private static int $countAccounts = 0; // Статический счетчик для уникальных поставщиков
    const TYPE_SETTLEMENT = 'settlement'; // Тип счета - расчетный
    const TYPE_CUMULATIVE = 'cumulative'; // Тип счета - накопительный
    private int $supplierId; // Идентификатор счета поставщика
    private string $accountType = self::TYPE_SETTLEMENT; // Тип счета

    // Метод для проверки допустимости типа счета
    private function isValidType(string $type): bool {
        // Проверка на допустимые типы счета
        return in_array($type, [self::TYPE_SETTLEMENT, self::TYPE_CUMULATIVE], true);
    }

    public function __construct(string $accountNumber, string $bankName, string $currency) {
        parent::__construct($accountNumber, $bankName, $currency);
        $this->supplierId = ++self::$countAccounts;
    }

    // Установка типа счета
    public function setType(string $type): void {
        // Проверка на допустимость типа счета
        if (!$this->isValidType($type)) {
            throw new InvalidArgumentException("Недопустимый тип счета: $type");
        }
        $this->accountType = $type;
    }

    // Получение типа счета
    public function getAccountType(): string {
        return $this->accountType;
    }

    // Получение идентификатора поставщика
    public function getSupplierId(): int {
        return $this->supplierId;
    }
}



// Класс для банковских счетов клиентов
class ClientBankAccount extends BankAccount {
    private static int $countAccounts = 0; // Статический счетчик для уникальных клиентов
    private int $clientId; // Идентификатор счета клиента
    private float $creditLimit = 0.0; // Кредитный лимит
    private int $accountRating = 0; // Рейтинг счета

    // Конструктор для инициализации счета клиента
    public function __construct(string $accountNumber, string $bankName, string $currency) {
        parent::__construct($accountNumber, $bankName, $currency);
        $this->clientId = ++self::$countAccounts; // Уникальный идентификатор клиента
    }

    // Установка кредитного лимита
    public function setCreditLimit(float $creditLimit): void {
        if ($creditLimit < 0) {
            throw new InvalidArgumentException("Недопустимый лимит счета: $creditLimit");
        }
        $this->creditLimit = $creditLimit;
    }

    // Получить кредитный лимит
    public function getCreditLimit(): float {
        return $this->creditLimit;
    }

    // Установка рейтинга счета
    public function setAccountRating(int $rating): void {
        $this->accountRating = $rating;
    }

    // Получить рейтинг счета
    public function getAccountRating(): int {
        return $this->accountRating;
    }

    // Получить идентификатор клиента
    public function getClientId(): int {
        return $this->clientId;
    }
}


// Абстрактный класс для поставщиков, клиентов и тд.
abstract class Party {
    private string $name; // Название
    private string $address; // Адрес
    private array $bankAccounts = []; // Банковские счета
    private string $phone; // Телефон

    public function __construct(string $name, string $phone, string $address) {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
    }

    // Получить название
    public function getName(): string {
        return $this->name;
    }

    // Установить название
    public function setName(string $name): void {
        $this->name = $name;
    }

    // Получить телефон
    public function getPhone(): string {
        return $this->phone;
    }

    // Установить телефон
    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    // Получить адрес
    public function getAddress(): string {
        return $this->address;
    }

    // Установить адрес
    public function setAddress(string $address): void {
        $this->address = $address;
    }

    // Добавить банковский счет
    public function addBankAccount(BankAccount $account): void {
        $this->bankAccounts[] = $account;
    }

    // Получить список банковских счетов
    public function getBankAccounts(): array {
        return $this->bankAccounts;
    }
}





// Класс для поставщиков
class Supplier extends Party {
    private static int $countSuppliers = 0; // Статический счетчик для уникальных поставщиков
    private int $supplierId; // Идентификатор поставщика
    private ?string $taxNumber = null; // Налоговый идентификационный номер
    private string $contactPerson; // Контактное лицо

    public function __construct(string $name, string $address, string $phone, string $contactPerson) {
        parent::__construct($name, $phone, $address);
        $this->supplierId = ++self::$countSuppliers;
        $this->contactPerson = $contactPerson;
    }

    // Установка налогового идентификатора
    public function setTaxNumber(string $taxNumber): void {
        $this->taxNumber = $taxNumber;
    }

    // Получить идентификатор поставщика
    public function getSupplierId(): int {
        return $this->supplierId;
    }

    // Получить налоговый идентификатор
    public function getTaxNumber(): ?string {
        return $this->taxNumber;
    }

    // Получить контактное лицо
    public function getContactPerson(): string {
        return $this->contactPerson;
    }
}


// Класс для клиентов
class Client extends Party {
    private static int $countClients = 0; // Статический счетчик для уникальных клиентов
    private int $clientId; // Идентификатор клиента
    private string $firstName; // Имя
    private string $lastName; // Фамилия
    private ?string $patronymic = null; // Отчество
    private ?DateTimeImmutable $birthDate = null; // Дата рождения
    private string $gender; // Пол

    public function __construct(string $lastName, string $firstName, string $patronymic, string $gender, string $address, string $phone) {
        parent::__construct("$firstName $lastName $patronymic", $phone, $address);
        $this->clientId = ++self::$countClients;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->patronymic = $patronymic;
        $this->gender = $gender;
    }

    // Получить идентификатор клиента
    public function getClientId(): int {
        return $this->clientId;
    }

    // Получить имя
    public function getFirstName(): string {
        return $this->firstName;
    }

    // Установить имя
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    // Получить фамилию
    public function getLastName(): string {
        return $this->lastName;
    }

    // Установить фамилию
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    // Получить отчество
    public function getPatronymic(): ?string {
        return $this->patronymic;
    }

    // Установить отчество
    public function setPatronymic(?string $patronymic): void {
        $this->patronymic = $patronymic;
    }

    // Получить дату рождения в заданном формате
    public function getBirthDate(string $format = 'd-m-Y'): ?string {
        return $this->birthDate?->format($format);
    }

    // Установить дату рождения
    public function setBirthDate(DateTimeImmutable $birthDate): void {
        $this->birthDate = $birthDate;
    }

    // Получить пол
    public function getGender(): string {
        return $this->gender;
    }

    // Установить пол
    public function setGender(string $gender): void {
        $this->gender = $gender;
    }
}