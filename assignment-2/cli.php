<?php
#!/usr/bin/php

class Record {
    public $amount;
    public $category;

    public function __construct($amount, $category) {
        $this->amount = $amount;
        $this->category = $category;
    }
}

class FinanceManagerCli {
    private $income_file;
    private $expense_file;
    private $incomes;
    private $expenses;
    private $income_categories;
    private $expense_categories;

    public function __construct($income_file = 'incomes.json', $expense_file = 'expenses.json') {
        $this->income_file = $income_file;
        $this->expense_file = $expense_file;
        $this->incomes = $this->loadRecords($this->income_file);
        $this->expenses = $this->loadRecords($this->expense_file);
        $this->income_categories = ['Salary', 'Bonus', 'Salami'];
        $this->expense_categories = ['Rent', 'Utility', 'Internet'];
    }

    private function loadRecords($file_path) {
        if (file_exists($file_path)) {
            $data = file_get_contents($file_path);
            $records_array = json_decode($data, true);
            $records = [];
            foreach ($records_array as $record) {
                $records[] = new Record($record['amount'], $record['category']);
            }
            return $records;
        }
        return [];
    }

    private function saveRecords($records, $file_path) {
        $records_array = array_map(function($record) {
            return ['amount' => $record->amount, 'category' => $record->category];
        }, $records);
        file_put_contents($file_path, json_encode($records_array));
    }

    public function addIncome($amount, $category) {
        $income = new Record($amount, $category);
        $this->incomes[] = $income;
        $this->saveRecords($this->incomes, $this->income_file);
    }

    public function addExpense($amount, $category) {
        $expense = new Record($amount, $category);
        $this->expenses[] = $expense;
        $this->saveRecords($this->expenses, $this->expense_file);
    }

    public function viewIncomes() {
        return $this->incomes;
    }

    public function viewExpenses() {
        return $this->expenses;
    }

    public function viewSavings() {
        $totalIncome = array_reduce($this->incomes, function($carry, $income) {
            return $carry + $income->amount;
        }, 0);
        $totalExpense = array_reduce($this->expenses, function($carry, $expense) {
            return $carry + $expense->amount;
        }, 0);
        return $totalIncome - $totalExpense;
    }

    public function viewCategories() {
        return [$this->income_categories, $this->expense_categories];
    }

    public function getIncomeCategories() {
        return $this->income_categories;
    }

    public function getExpenseCategories() {
        return $this->expense_categories;
    }
}

$manager = new FinanceManagerCli();

while (true) {
    echo "\nOptions:\n";
    echo "1. Add income\n";
    echo "2. Add expense\n";
    echo "3. View incomes\n";
    echo "4. View expenses\n";
    echo "5. View savings\n";
    echo "6. View categories\n";
    echo "7. Exit\n";

    $option = readline("Enter your option: ");

    switch ($option) {
        case '1':
            $amount = (float)readline("Enter income amount: ");
            $categories = $manager->getIncomeCategories();
            echo "Select a category:\n";
            foreach ($categories as $index => $category) {
                echo ($index + 1) . ". " . $category . "\n";
            }
            $categoryIndex = (int)readline("Enter category number: ") - 1;
            if ($categoryIndex >= 0 && $categoryIndex < count($categories)) {
                $manager->addIncome($amount, $categories[$categoryIndex]);
                echo "Income added.\n";
            } else {
                echo "Invalid category.\n";
            }
            break;
        case '2':
            $amount = (float)readline("Enter expense amount: ");
            $categories = $manager->getExpenseCategories();
            echo "Select a category:\n";
            foreach ($categories as $index => $category) {
                echo ($index + 1) . ". " . $category . "\n";
            }
            $categoryIndex = (int)readline("Enter category number: ") - 1;
            if ($categoryIndex >= 0 && $categoryIndex < count($categories)) {
                $manager->addExpense($amount, $categories[$categoryIndex]);
                echo "Expense added.\n";
            } else {
                echo "Invalid category.\n";
            }
            break;
        case '3':
            $incomes = $manager->viewIncomes();
            echo "\nIncomes:\n";
            foreach ($incomes as $income) {
                echo "Amount: {$income->amount}, Category: {$income->category}\n";
            }
            break;
        case '4':
            $expenses = $manager->viewExpenses();
            echo "\nExpenses:\n";
            foreach ($expenses as $expense) {
                echo "Amount: {$expense->amount}, Category: {$expense->category}\n";
            }
            break;
        case '5':
            $savings = $manager->viewSavings();
            echo "\nTotal savings: {$savings}\n";
            break;
        case '6':
            list($income_categories, $expense_categories) = $manager->viewCategories();
            echo "\nIncome Categories:\n";
            foreach ($income_categories as $category) {
                echo $category . "\n";
            }
            echo "\nExpense Categories:\n";
            foreach ($expense_categories as $category) {
                echo $category . "\n";
            }
            break;
        case '7':
            exit;
        default:
            echo "Invalid option. Please try again.\n";
    }
}
