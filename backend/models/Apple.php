<?php
namespace backend\models;

//use Yii;
use yii\base\Model;

/**
 * Apple модель/класс
 */
class Apple extends Model
{
    private $id, $color, $size = 1, $birth_date, $fall_date;

    /**
     * Рождение яблока
     *
     * @param string $color цвет яблока, иначе - случайный
     */
    function __construct($color=null)
    {
        parent::init();

        if ($color) $this->color = $color;
        else $this->color = sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));

        $this->birth_date = rand(time()-60*60*5, time());   // делать испорченные бессмысленно

        $this->insertApple();
    }

    public function __get($property)
    {
        switch ($property) {
            case 'color': return $this->color;  break;
            case 'size': return $this->size;    break;
            case 'state':
                if ($this->size === 0) return appleState::deleted;
                if ($this->fall_date) {
                    if (time() - $this->fall_date >= 60*60*5)  return appleState::rotten;  // более 5 часов - испортилось
                    else return appleState::onGround;
                }
                return appleState::onTree;
        }
        return null;
    }

    /**
     * Яблоко упало
     */
    public function fallToGround()
    {
        if (!$this->fall_date) {
            $this->fall_date = time();
            $this->updateApple();
        }
        else  throw new \Exception('Яблоко уже на земле!');
    }

    /**
     * Яблоко откусили
     *
     * @param int $percent сколько процентов откусили
     */
    public function eat($percent)
    {
        if ($this->state === \appleState::onTree)  throw new \Exception('Съесть нельзя, яблоко на дереве!');
        if ($this->state === \appleState::rotten)  throw new \Exception('Лучше не кушать - оно гнилое');
        if ($this->state === \appleState::deleted)  throw new \Exception('Яблоко уже съедено!');

        if ($this->size*100 <= $percent) $this->size = 0;
        else $this->size -= $percent/100;

        $this->updateApple();
    }

    /**
     * Удалить яблоко
     */
    public function delete()
    {
        if ($this->state !== appleState::deleted) {
            $this->size = 0;
            $this->updateApple();
        }
        else  throw new \Exception('Яблоко уже съедено!');
    }

    /**
     * Добавить яблоко в базу
     */
    private function insertApple()
    {
        $apple = new AppleAR();
        $apple->color = $this->color;
        $apple->birth_date = $this->birth_date;
        if ($apple->save()) {
            $this->id = $apple->id;
            return true;
        }
        else  return false;
//        var_dump(get_object_vars($this));
    }

    /**
     * Обновить состояние яблока в базе
     */
    private function updateApple()
    {
        $apple = AppleAR::findOne($this->id);
        $apple->size = $this->size;
        $apple->fall_date = $this->fall_date;
        $apple->state = $this->state;
        return $apple->save();
    }

}
