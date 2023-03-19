<?php

namespace QuizApp\Model;

use Exception;

class UserModel extends Database
{

    /**
     * @throws Exception
     */
    public function getUsers($limit)
    {
        return $this->select('SELECT * FROM user ORDER BY id LIMIT ?', ['i', $limit]);
    }
}
