<?php


namespace App;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo, $queryFactory;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=exam;charset=utf8;", "root", "root");
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getAll($table) {


        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table);

        $sth = $this->pdo->prepare($select->getStatement());


        $sth->execute($select->getBindValues());


        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function select($what, $table, $where = null) {


        $select = $this->queryFactory->newSelect();

        $select->cols([$what])
            ->from($table);
        if(!empty($where)) {
            $select->where($where);
        }


        $sth = $this->pdo->prepare($select->getStatement());



        $sth->execute($select->getBindValues());


        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($data = [], $table) {

        $insert = $this->queryFactory->newInsert();

        $insert->into($table)
        ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());


        $sth->execute($insert->getBindValues());


        $name = $insert->getLastInsertIdName('id');
        $id = $this->pdo->lastInsertId($name);
        return $id;

    }

    public function update($what = [], $table, $where = null) {


        $update = $this->queryFactory->newUpdate();

        $update->table($table)
            ->cols($what);
        if(!empty($where)) {
            $update->where($where);
        }


        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());


    }

    public function delete($table, $where = null) {


        $delete = $this->queryFactory->newDelete();

        $delete->from($table)
            ->where($where);

        $sth = $this->pdo->prepare($delete->getStatement());


        $sth->execute($delete->getBindValues());


    }
}