<?php
    namespace App\classes;

    class Transaction{
        protected $from;
        protected $to;
        protected $amount;
        protected $type;
        protected $date;
        protected $fileStorage;

        public function __construct($from, $to, $amount, $type)
        {
            $this->from = $from;
            $this->to = $to;
            $this->amount = $amount;
            $this->type = $type;
            $this->date = date('Y-m-d H:i:s');
            $this->fileStorage = new FileStorage();
        }

        public function recordTransaction()
        {
            $transactions = $this->fileStorage->load('data/transactions.json');
            $transactions[] = [
                'from' => $this->from,
                'to' => $this->to,
                'amount' => $this->amount,
                'type' => $this->type,
                'date' => $this->date
            ];
            $this->fileStorage->save('data/transactions.json', $transactions);
        }
    }
