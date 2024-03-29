<?php

namespace BlackRook\TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReportRepository extends EntityRepository
{
    /**
     * @param DateTime $current
     * @return DateTime $start
     */
    public function getStartOfDay(DateTime $current)
    {
        $currentYear = $current->format('Y');
        $currentMonth = $current->format('m');
        $currentDay = $current->format('d');
        $start = new DateTime();
        $start->setDate($currentYear, $currentMonth, $currentDay);
        $start->setTime(0, 0, 0);
        return $start;
    }

    /**
     * @param DateTime $current
     * @return DateTime $end
     */
    public function getEndOfDay(DateTime $current)
    {
        $currentYear = $current->format('Y');
        $currentMonth = $current->format('m');
        $currentDay = $current->format('d');
        $end = new DateTime();
        $end->setDate($currentYear, $currentMonth, $currentDay);
        $end->setTime(23, 59, 59);
        return $end;
    }

    /**
     * If start of week is sunday, then offset is "Sunday"
     * If start of week is monday, then offset is "Monday"
     * If start of week is saturday, then offset is "Saturday"
     *
     * @param DateTime $current
     * @param string $startDay
     * @return DateTime $start
     */
    public function getStartOfWeek(DateTime $current, $startDay = "Sunday")
    {
        $startDay = ucfirst($startDay);

        $start = new DateTime();
        $start->setTimestamp($current->getTimestamp());

        $currentDay = $current->format('l');
        if ($currentDay != $startDay) {
            $start->modify("Last ".$startDay);
        }
        $start->setTime(0, 0, 0);
        return $start;
    }

    /**
     * If end of week is saturday, then offset is "Saturday"
     * If end of week is sunday, then offset is "Sunday"
     * If end of week is friday, then offset is "Friday"
     *
     * @param DateTime $current
     * @param string $endDay
     * @return DateTime $start
     */
    public function getEndOfWeek(DateTime $current, $endDay = "Saturday")
    {
        $endDay = ucfirst($endDay);

        $end = new DateTime();
        $end->setTimestamp($current->getTimestamp());

        $currentDay = $current->format('l');
        if ($currentDay != $endDay) {
            $end->modify("Next ".$endDay);
        }
        $end->setTime(23, 59, 59);
        return $end;
    }

    /**
     * @param DateTime $current
     * @return DateTime $start
     */
    public function getStartOfMonth(DateTime $current)
    {
        $currentYear = $current->format('Y');
        $currentMonth = $current->format('m');
        $start = new DateTime();
        $start->setDate($currentYear, $currentMonth, 1);
        $start->setTime(0, 0, 0);
        return $start;
    }

    /**
     * @param DateTime $current
     * @return DateTime $end
     */
    public function getEndOfMonth(DateTime $current)
    {
        $end = $this->getNextMonth($current);
        $end->modify("-1 second");
        return $end;
    }

    /**
     * @param DateTime $current
     * @return DateTime $next
     */
    public function getNextMonth(DateTime $current)
    {
        $currentYear = $current->format('Y');
        $currentMonth = $current->format('m');
        $nextMonth = new DateTime();
        $nextMonth->setDate($currentYear, $currentMonth + 1, 1);
        $nextMonth->setTime(0, 0, 0);
        return $nextMonth;
    }
}