<?php

namespace QuizApp\Controller\Api;

use QuizApp\Model\Database;

class UserAnswersController
{
    private \PDO $conn;
    protected Database $db;

    public function __construct()
    {
        // connect to DB
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function saveUserAnswer($quiz_id, $question_id, $answer_id, $user_id)
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO user_answers (quiz_id, question_id, answer_id, user_id) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('iiii', $quiz_id, $question_id, $answer_id, $user_id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function getUserAnswersByQuizIdAndUserId($quiz_id, $user_id)
    {
        $stmt = $this->conn->prepare(
            'SELECT q.question, a.answer, a.is_correct 
                        FROM user_answers ua 
                            INNER JOIN quiz q 
                                ON ua.question_id = q.id 
                            INNER JOIN answer a 
                                ON ua.answer_id = a.id 
                        WHERE ua.quiz_id = ? 
                          AND ua.user_id = ?'
        );
        $stmt->bind_param('ii', $quiz_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_answers = array();
        while ($row = $result->fetch_assoc()) {
            $user_answer = array(
                'question' => $row['question'],
                'answer' => $row['answer'],
                'is_correct' => $row['is_correct']
            );
            $user_answers[] = $user_answer;
        }
        return $user_answers;
    }

}
