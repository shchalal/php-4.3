<?php
declare(strict_types=1);

class Person
{
    private string $name;
    private string $login;
    private string $password;

    public function __construct(string $name, string $login, string $password)
    {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
    }

   
    public function __get($property)
    {
        echo "Попытка получить свойство '$property'\n";
        return $this->$property ?? null;
    }

    public function __set($property, $value)
    {
        echo "Попытка установить свойство '$property' = $value\n";
        $this->$property = $value;
    }

    
    public function __sleep(): array
    {
        echo "Выполняется сериализация объекта Person...\n";
        return ['name', 'login', 'password'];
    }

    
    public function __wakeup(): void
    {
        echo "Объект Person десериализован! Добро пожаловать обратно, {$this->name}!\n";
    }

  
    public function __toString(): string
    {
        return "Имя: {$this->name}, Логин: {$this->login}, Пароль: {$this->password}";
    }
}


class PeopleList implements Iterator
{
    private array $people = [];
    private int $position = 0;

    public function addPerson(Person $person): void
    {
        $this->people[] = $person;
    }

    public function current(): mixed
    {
        return $this->people[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->people[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}

$person = new Person('Ivan', 'ivan123', 'pass321');

$serialized = serialize($person);
echo "\nСериализованный объект:\n$serialized\n\n";


$replaced = str_replace('ivan123', 'alex456', $serialized);
echo "После замены логина (того же размера):\n$replaced\n\n";


$unserialized = unserialize($replaced);
echo "После десериализации:\n";
echo $unserialized . "\n\n";

$list = new PeopleList();
$list->addPerson($person);
$list->addPerson(new Person('Olga', 'olga777', 'olga_pass'));
$list->addPerson(new Person('Pavel', 'pavel999', 'pa$$word'));

echo "Список людей (через foreach):\n";
foreach ($list as $p) {
    echo $p . "\n";
}
