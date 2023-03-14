<?php
// Имя
namespace GeekBrains\LevelTwo\Person;

class Name
{
  private string $firstname, $lastname;

  public function __construct(string $firstname, string $lastname)
  {
    $this->lastname = $lastname;
    $this->firstname = $firstname;
  }

  public function __toString()
  {
    return $this->firstname . ' ' . $this->lastname . PHP_EOL;
  }
}