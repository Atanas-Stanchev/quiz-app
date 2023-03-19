<?php

namespace QuizApp\Controller\Api;

use PDO;
use QuizApp\Model\Database;

class QuizController
{

    private \PDO $conn;
    protected Database $db;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');
        // connect to DB
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getQuizQuestions($quizId)
    {
        $id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : die();
        var_dump($id);
        exit();
        $stmt = $this->db->select(
            'SELECT q.id, q.text, a.name AS author, GROUP_CONCAT(ans.text ORDER BY ans.id SEPARATOR ' | ') AS answers
                        FROM question q
                                 LEFT JOIN answer ans ON q.id = ans.question_id
                                 LEFT JOIN author a ON q.id = a.id
                        WHERE q.quiz_id = ?
                        GROUP BY q.id
                        ORDER BY q.id'
        );

        $stmt->bindParam('i', $quizId);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $questions = array();

            while ($row = $result->fetch_assoc()) {
                $question = array(
                    'id' => $row['id'],
                    'text' => $row['text'],
                    'author' => $row['author'],
                    'answers' => explode(',', $row['answers'])
                );

                $questions[] = $question;
            }

            echo json_encode($questions);
        }

        return null;
    }
}
