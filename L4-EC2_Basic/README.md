<img src="https://welastic.pl/wp-content/uploads/2021/10/logo-black.svg" alt="Welastic logo" width="100" align="left">
<br><br>
<br><br>
<br><br>

# Creating Amazon EC2 Instances (for Linux)

## LAB Overview

#### This lab leads you through the steps to launch and configure your first virtual machine in the Amazon cloud. You will learn about using Amazon Machine Images to launch Amazon EC2 instances, creating key pairs for SSH authentication, securing network access to Amazon EC2 instances with security groups, automatically configuring Amazon EC2 instances with bootstrapping scripts, and attaching Elastic IPs to Amazon EC2 instances to provide static Internet addresses. At the end of this lab you will have deployed a simple web server which includes an informational page to display details of your virtual web server instance.

## Download PuTTY (MS Windows) 

If you do not already have the PuTTY client installed on your machine, you can download and then launch it from here: [www.chiark.greenend.org.uk/~sgtatham/putty/](www.chiark.greenend.org.uk/~sgtatham/putty/)



## Task 1: Launch a Linux instance

In this lab, you will launch a default Amazon Linux instance with an Apache PHP web server installed on
initialization.

1. On the **Services** menu, click **EC2**.
2. Click **Launch Instance**.
2. In the box on the right choose number of instances you want to launch - 1 is default, leave this setting as it is.
3. Set name and additional tags for EC2 instance, i.e Key: Env - Value: Test 
4. Because you require a Linux instance, in the row select the basic 64-bit **Amazon Linux 2 AMI (HVM), SSD Volume Type**, which will be the default option.
5. On the **Instance Type** list, select the **t2.micro** instance type, which is the lowest-cost option, should be automatically selected.
6. Choose ssh key pair - if it wasn't created before, create new one and download private key to your device. As a format choose .pem (SSH format - Linux and MacOs terminals) or ppk - Puttys format.
7. On the **Network Settings** click Edit and:
   * **VPC:** The one you created (StudentX_VPC)
   * **Subnet**: StudentX_Public1
   * **Auto-assign Public IP**: Enable
   * **Security-Group**: Select existing security group --> select a WebServers Security Group you created in Lab3: VPC, Task 5. 
8. Configure storage - leave the default settings.
9. Scroll down and expand the **Advanced Details** section. 
10. Scroll till **User data** and paste there snippet from point **11**. 

Since you will be using your Amazon EC2 instance as a web server, you need to ensure that the Apache
HTTPd server is up and running, and that the PHP programming language is installed. We can accomplish
this with a simple Linux shell script. The script below installs HTTPd and PHP using the yum package
manager, and then starts the HTTPd server.

11. Copy the initialization script seen below. Paste the script into the **User data** box:

```shell
#!/bin/sh
yum -y install httpd php php-mysql \n
chkconfig httpd on \n
service httpd start
```

You can also copy a script from [bootstrap.sh](bootstrap.sh)

This will automatically install and start the Apache Web server when the instance is created and launches.
**Note:** You can copy script from **bootstrap.sh**. If you type the script, use SHIFT+ENTER for new lines in the
text box.
 
12. Take a look on the summary.
13. Click **Launch instance**. 

**Note:** You may see a warning on this screen that **Your security group is open to the world**. This is a result
of not restricting SSH access to your machine, as described above. For the purposes of this lab only, you
may ignore this warning.



14. On the status page, which notifies you that your instances are launching, click **View All Instances**.

The Instances page of the Amazon EC2 Dashboard displays the list of all running Amazon EC2 instances in
the currently selected region. You can see the status of your instance here. If the status is not Running, wait a few minutes and refresh the list.

15. Select your instance to display a list of details and status update in the lower pane.

16. Copy the **IPv4 Public IP** value to your Clipboard. 


## Task 2: Connect to your EC2 instance

#### Instructions for Windows Users: Connecting to your Amazon EC2 Instance via SSH

**Note** This section is for Windows users only. If you are running Mac OS or Linux, skip to the next section. 

In this section, you will use the PuTTY Secure Shell (SSH) client and your server's public IP address to connect to your server. The downloaded private key in step 18 is in **pem** format. To use it with putty you need to convert it. Converting your private key is needed only on **old** AWS interface. The new one allows you to download key in both formats. You can freely skip this part to the next points.

1. Start PuTTYgen (for example, from the **Start** menu, choose **All Programs > PuTTY > PuTTYgen**). 

2. Under **Type of key to generate**, choose **RSA**. (**SSH-2 RSA** in older putty versions). 

3. Choose **Load**. By default, PuTTYgen displays only files with the extension .ppk. To locate your .pem file, 

    select the option to display files of all types. 

4. Select your .pem file for the key pair that you specified when you launched your instance, and then 

    choose **Open**. Choose **OK** to dismiss the confirmation dialog box. 

5. Choose **Save private key** to save the key in the format that PuTTY can use. PuTTYgen displays a warning 

    about saving the key without a passphrase. Choose **Yes**. 

6. Specify the same name for the key that you used for the key pair (for example, my-key-pair). PuTTY 

    automatically adds the .ppk file extension. 

7. Start PuTTY (from the **Start** menu, choose **All Programs > PuTTY > PuTTY**). 

8. In the **Host Name** box, enter **ec2-user@**<public IP from step 22>. 

9. In the **Category** list, expand **SSH.** 

10. Click **Auth** (don't expand it). 

11. In the **Private key file for authentication box**, browse to the your PPK file and double-click it. 

12. Click **Open**. 

13. Click **Yes** when prompted to allow a first connection to this remote SSH server. 

#### Instructions for Mac OS and Linux Users: Connecting to your Amazon EC2 Instance via SSH

**Note** This section is for Mac OS and Linux users only. If you are running Windows, skip to the next section.

14. Open your favourite Terminal application. 

15. Correct permision to the key file by typing: **chmod 600** <path_to_your_pem_file>. 

16. Connect to your EC2 instance by typing: **ssh -i** <path_to_your_pem_file> **ec2-user@**<public IP of your EC2 instance>. 

Can you connect? If no can you tell why and you know how to fix it? 


## Task 3: Create a PHP Web Page on Your EC2

The AMI has already been customized with the installation of Apache and PHP from the script you entered
as user data when the instance was launched. Modify the web server by adding an index.php file.

1. Type the following into PuTTY or Terminal window in order to create an index.php file at the root of your HTTP web server HTML document directory: 

    

```bash
cd /var/www/html 
sudo nano index.php
```

2. Copy and paste the following code into the console (file index.php in lab folder): 

```php
<?php
      $url = "http://169.254.169.254/latest/meta-data/instance-id";
      $instance_id = file_get_contents($url);
      echo "<h1>Hello World</h1>";
      echo "Instance ID: <b>" . $instance_id . "</b><br/>";
      $url = "http://169.254.169.254/latest/meta-data/placement/availability-zone";
      $zone = file_get_contents($url);
      echo "Zone: <b>" . $zone . "</b><br/>";
      $url = "http://169.254.169.254/latest/meta-data/local-ipv4/";
      $privateIP = file_get_contents($url);
      echo "Private IP: <b>" . $privateIP .  "</b><br/>";

?>
```
You can download an entire file [index.php](index.php)

3.  Press CTRL+O, ENTER to save your document as index.php. 
4.  Press CTRL+X to exit the Nano editor.
5.  Run httpd service on instance
```shell
sudo service httpd start
```
6.  Open a new browser window, and then paste the public IP value into the address bar.

Your **Instance ID** and **Availability Zone** should be displayed in the browser.



## END LAB

This is the end of the lab. Don’t terminate the instance, it will be used in the next labs.

<br><br>

<p align="right">&copy; 2022 Welastic Sp. z o.o.<p>
