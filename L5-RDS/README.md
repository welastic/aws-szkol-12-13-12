<img src="https://welastic.pl/wp-content/uploads/2021/10/logo-black.svg" alt="Welastic logo" width="100" align="left">
<br><br>
<br><br>
<br><br>

# Introduction to Amazon Aurora

## LAB Overview

#### This lab introduces you to Amazon Aurora using the AWS Management Console.

## Task 1: Create new DB Subnet Group
DB subnet group defines which subnets and IP ranges the DB instance can use in the VPC you selected. During Aurora instance creation is possible to let a wizard create a subnet group automatically. But for our lab, we want to have a full control of which subnets will be selected for Aurora cluster.

1. In the AWS Management Console, on the **Services** menu, click **RDS**.
2. In the left navigation pane, click **Subnet groups**.
3. Click **Create DB Subnet Group**.
4. On **Create DB subnet group**, step configure the following:

* **Name**: StudentX-DBSG
* **Description**: Your name
* **VPC**: Select your VPC created in LAB-VPC
* Select **eu-west-1a** and **eu-west-1b** in Availability Zones

If you followed VPC-LAB instructions correctly,
fours subnet should appear in **Subnets in this subnet group**. Now you need to select the private subnets
- 10.X.10.0/24 and 10.X.11.0/24 (where X indicates your student number).

5. Click **Create**.

## Task 2: Create a Aurora Cluster

In this task, you will create an Amazon Aurora cluster

1. In the left navigation pane, click **Databases**.
2. Click **Create database** button.
3. On **Create database**, select
   * Creation method: **Standard Create**
   * Engine type: **Amazon Aurora**
   * Edition: **Amazon Aurora MySQL-Compatible Edition**
   * Version: **Leave suggested option**
   * Template: **Production**
   * DB cluster identifier: **StudentX-cluster**
   * Master password: **Type password and save it in notepad**
   * DB instance size: **Burstable classes**, select instance form list - **db.t3.small**
   * Multi-AZ deployment: **Create an Aurora Replica/Reader node in a different AZ (recommended for scaled availability)**
   * Virtual Private Cloud (VPC): **Select your VPC**
   * Subnet group: **Select group created in Task 1**
   * VPC security group: **Choose existing**
   * Existing VPC security groups: 
     * Select: **StudentX_DB**
     * Deselect: **default**
   * Expand **Addotional configuration**
   * Initial database name: **studentXdb**
   * Delection protection: **Unselect**
4. Click **Create database**.
5.  Wait until **Successfully created database studentX-mysql.** green information appear.
6.  Click **View connections details**.
7.  Copy **Master username**, **Master password** and **Endpoint** values to the notepad.

This is the only time you will be able to view this password. However you can modify your database to create a new password at any time.

## Task 3: Test your DB cluster

You will now connect to Aurora database from your EC2 server. 

1. Connect to your  laboratory EC2 instance over SSH.
2. Go you website folder: 

```bash
sudo su -
yum -y install httpd php php-mysql
chkconfig httpd on
service httpd start
cd /var/www/html
```

3. Create a new file **auroradb.php**:

```bash
sudo nano auroradb.php
```



4. Paste the following code ( code of the file [db.php](db.php))

```php
<?php
$servername = "enter your ENDPOINT";
$database = "studentXdb";
$username = "admin";
$password = "enter master password";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}
  echo "<h1>Great!</h1><br>Connected successfully to:<b> $servername</b>";
?>
```

5.  Provide correct values for: **servername**, **database** and **password**. Servername you can find in your cluster endpoints. 
6.  Press CTRL+O, ENTER to save your document. 
7.  Press CTRL+X to exit the Nano editor.
8.  Open a new browser window, and then paste the public DNS value into the address bar and add auroradb.php.

Example: http://34.240.160.241/auroradb.php

## Task 4: Test your DB instance with cli (optional)

1. Connect to your  laboratory EC2 instance over SSH.

2. Install mysql tool:

 ```shell
 sudo yum install mysql -y
 ```

3. Connect to the database:

```bash
mysql -u admin -p -h enter_db_entpoint
```

4. List databases:

```my
show databases;
```

## Task 5: Test failover

It is possible to manually switch cluster node roles. Check the following steps. 

1.  Find your database cluster end expand it.
2.  Select you **Reader** node.
3.  Click **Actions** button and select **Failover**
4.  Click **Failover** to confirm.
5.  Click refresh button and verify changes. 

## END LAB

This is the end of this lab. Go over following steps to remove database cluster.

30. In the AWS Management Console, on the **Services** menu, click **RDS**.
34. In the left navigation pane, click **Databases**.
35. Find and expand your cluster.
36. Click on **Reader** node, select **Actions** button and choose **Delete**
37. Confirm by typing **delete me** and click **Delete**
38. Do the same with **Writer** node.
39. Skip final snapshot.

<br><br>

<p align="right">&copy; 2022 Welastic Sp. z o.o.<p>
