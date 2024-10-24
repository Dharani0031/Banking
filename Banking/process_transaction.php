<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $account_number = $_POST['account_number'];
    $action = $_POST['action'];
    $amount = $_POST['amount'];
    $recipient_account = $_POST['recipient_account'];

    // Validate inputs
    if (empty($username) || empty($account_number) || empty($action) || ($action == 'transfer' && empty($recipient_account))) {
        echo "All fields are required!";
    } else {
        if ($action == 'withdraw' || $action == 'deposit') {
            $sql = "SELECT balance FROM accounts WHERE username='$username' AND account_number='$account_number'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $current_balance = $row['balance'];

                if ($action == 'withdraw') {
                    if ($amount > $current_balance) {
                        echo "Insufficient funds!";
                    } else {
                        $new_balance = $current_balance - $amount;
                        $sql = "UPDATE accounts SET balance=$new_balance WHERE username='$username' AND account_number='$account_number'";
                        if ($conn->query($sql) === TRUE) {
                            echo "Withdrawal successful!";
                        } else {
                            echo "Error: " . $conn->error;
                        }
                    }
                } elseif ($action == 'deposit') {
                    $new_balance = $current_balance + $amount;
                    $sql = "UPDATE accounts SET balance=$new_balance WHERE username='$username' AND account_number='$account_number'";
                    if ($conn->query($sql) === TRUE) {
                        echo "Deposit successful!";
                    } else {
                        echo "Error: " . $conn->error;
                    }
                }
            } else {
                echo "Account not found!";
            }
        } elseif ($action == 'transfer') {
            $sql = "SELECT balance FROM accounts WHERE account_number='$account_number'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $current_balance = $row['balance'];

                if ($amount > $current_balance) {
                    echo "Insufficient funds!";
                } else {
                    // Update sender account
                    $new_balance_sender = $current_balance - $amount;
                    $sql = "UPDATE accounts SET balance=$new_balance_sender WHERE account_number='$account_number'";
                    if ($conn->query($sql) === TRUE) {
                        // Update receiver account
                        $sql = "SELECT balance FROM accounts WHERE account_number='$recipient_account'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $current_balance_receiver = $row['balance'];
                            $new_balance_receiver = $current_balance_receiver + $amount;

                            $sql = "UPDATE accounts SET balance=$new_balance_receiver WHERE account_number='$recipient_account'";
                            if ($conn->query($sql) === TRUE) {
                                echo "Transfer successful!";
                            } else {
                                echo "Error: " . $conn->error;
                            }
                        } else {
                            echo "Recipient account not found!";
                        }
                    } else {
                        echo "Error: " . $conn->error;
                    }
                }
            } else {
                echo "Sender account not found!";
            }
        }
    }
}

$conn->close();
?>
