<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TimePeriod implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $startDateTime;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDateTime;

    public static function create(?\DateTime $startDateTime, ?\DateTime $endDateTime): TimePeriod
    {
        $timePeriod = new static();
        $timePeriod->startDateTime = $startDateTime;
        $timePeriod->endDateTime = $endDateTime;

        return $timePeriod;
    }

    public static function fromTimePeriod(TimePeriod $source): TimePeriod
    {
        return static::create($source->getStartDateTime(), $source->getEndDateTime());
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $timePeriodData = [];

        if (empty($this->startDateTime) && empty($this->endDateTime)) {
            return $timePeriodData;
        }

        if (!empty($this->startDateTime)) {
            $timePeriodData['start_date_time'] = $this->startDateTime->format(\DateTime::ATOM);
        }

        if (!empty($this->endDateTime)) {
            $timePeriodData['end_date_time'] = $this->endDateTime->format(\DateTime::ATOM);
        }

        return $timePeriodData;
    }
}
