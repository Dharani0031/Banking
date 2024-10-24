<!DOCTYPE html>
<html>
<head>
    <title>ATM Simulation</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleRecipient() {
            var action = document.getElementById('action').value;
            var recipientDiv = document.getElementById('recipient_div');
            if (action === 'transfer') {
                recipientDiv.style.display = 'block';
            } else {
                recipientDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h1>ATM Simulation</h1>
    <form method="post" action="process_transaction.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="account_number">Account Number:</label>
        <input type="text" id="account_number" name="account_number" required><br><br>

        <label for="action">Action:</label>
        <select id="action" name="action" onchange="toggleRecipient()" required>
            <option value="withdraw">Withdraw</option>
            <option value="deposit">Deposit</option>
            <option value="transfer">Transfer</option>
        </select><br><br>

        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required><br><br>

        <div id="recipient_div" style="display: none;">
            <label for="recipient_account">Recipient Account Number:</label>
            <input type="text" id="recipient_account" name="recipient_account"><br><br>
        </div>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
