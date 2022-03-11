<?php

namespace App\Infrastructure\Entity\Strategy;

use App\Infrastructure\Repository\Strategy\StrategyRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StrategyRepository::class)
 */
class Strategy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Rule::class, mappedBy="strategy", cascade={"persist"})
     */
    private ?Collection $rules;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRules(): ?Collection
    {
        return $this->rules;
    }

    public function setRules(Collection $rules): void
    {
        $this->rules = $rules;
    }

    public function addRules(Rule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules->add($rule);
            $rule->setStrategy($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): self
    {
        if ($this->rules->removeElement($rule) && $rule->getStrategy() === $this) {
            $rule->setStrategy(null);
        }

        return $this;
    }
}
