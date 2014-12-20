<?php
namespace Craft;

use Carbon\Carbon;
use Craft\Plugins\Postmaster\Interfaces\TransportInterface;

class Postmaster_TransportModel extends BaseModel implements TransportInterface
{
	public function now()
	{
		return Carbon::now(craft()->getTimezone());
	}

	public function getSendDate()
	{
		if(!$this->sendDate || is_string($this->sendDate))
		{
			$this->sendDate = Carbon::parse($this->sendDate);
		}

		if($this->sendDate instanceof Carbon)
		{
			return $this->sendDate;
		}

		return $this->now();
	}

	public function setSendDate(Carbon $date)
	{
		$this->sendDate = Carbon::parse($date);
	}

	public function shouldSend()
	{
		return $this->getSendDate()->isPast() || $this->getSendDate()->diffInSeconds($this->now()) == 0;
	}

	protected function defineAttributes()
    {
        return array(
            'service' => AttributeType::Mixed,
            'settings' => AttributeType::Mixed,
            'data' => AttributeType::Mixed,
            'senderId' => AttributeType::String,
            'sendDate' => array(AttributeType::Mixed, 'default' => false),
            'queueId' => array(AttributeType::Mixed, 'default' => false)
        );
    }
}