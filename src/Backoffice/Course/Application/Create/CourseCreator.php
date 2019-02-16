<?php

declare(strict_types = 1);

namespace CodelyTv\Backoffice\Course\Application\Create;

use CodelyTv\Backoffice\Course\Domain\Course;
use CodelyTv\Backoffice\Course\Domain\CourseDescription;
use CodelyTv\Backoffice\Course\Domain\CourseRepository;
use CodelyTv\Backoffice\Course\Domain\CourseTitle;
use CodelyTv\Shared\Domain\Bus\Event\DomainEventPublisher;
use CodelyTv\Shared\Domain\CourseId;

final class CourseCreator
{
    private $repository;
    private $publisher;

    public function __construct(CourseRepository $repository, DomainEventPublisher $publisher)
    {
        $this->repository = $repository;
        $this->publisher  = $publisher;
    }

    public function create(CourseId $id, CourseTitle $title, CourseDescription $description): void
    {
        $course = Course::create($id, $title, $description);

        $this->repository->save($course);

        $this->publisher->publish(...$course->pullDomainEvents());
    }
}