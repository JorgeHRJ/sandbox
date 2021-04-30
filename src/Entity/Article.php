<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="article", indexes={@ORM\Index(name="article_published_at_idx", columns={"article_published_at"})})
 */
class Article
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(name="article_title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var Category|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinColumn(name="article_category", referencedColumnName="category_id", nullable=false)
     */
    private $category;

    /**
     * @var Tag[]|Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="articles", cascade={"persist"})
     * @ORM\JoinTable(name="article_tag",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="article_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")}
     * )
     */
    private $tags;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(name="article_published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addArticle($this);
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeArticle($this);
        }
    }

    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
