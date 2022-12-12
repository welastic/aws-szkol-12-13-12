<img src="https://welastic.pl/wp-content/uploads/2021/10/logo-black.svg" alt="Welastic logo" width="100" align="left">
<br><br>
<br><br>
<br><br>

# Building Your First Amazon Virtual Private Cloud

## LAB Overview

#### In this lab session, you will create a basic virtual private cloud (VPC) manually. This VPC will include a frontend web server and a backend RDS subnets.

![](img/VPC_redundand.png)

In this lab you will create:

* Create a base Amazon Virtual Private Cloud (VPC) in Ireland Region
* Create a public subnet for your web server
* Create an Internet Gateway
* Create a route table and add a route so that people can access your web server from the Internet
* Create a security group to restrict only HTTP traffic to your web server 
* Create two private subnets for your Backend MySQL server
* Create a security group for your private subnet to only allow MySQL traffic from the public subnet 

## Task 1: Create a VPC
In this task, you will create a base VPC

1. In the **AWS Management Console**, on the **Services** menu, click **VPC**.
2. In the navigation pane on the left, click **Your VPCs**.
3. Click **Create VPC**.
4. In the **Create VPC** window use the following:
   * **Name tag:** StudentX_VPC (where X is your Student number)
   * **IPv4 CIDR block:** 10.X.0.0/16 (where X is your Student number)
   * Click **Create** and **Close**.

## Task 2: Create a Public Subnets

In this task, you will create a public subnets for frontend web servers.

1. In the Navigation pane on the left, click **Subnets**.
2. Click **Create Subnet**
3. In the **Create Subnet** window configure the following:
   * **VPC:** StudentX_VPC (select your VPC created in Task 1)
   * **Subnet name:** StudentX_Public1
   * **Availability Zone**: eu-west-1a (for Ireland region)
   * **IPv4 CIDR block**: 10.X.0.0/24
   * Click **Create**
4. Create second public subnet in the same way. Make sure you select the next Availability Zone. Use Name tag: **StudentX_Public2**, CIDR block: **10.X.1.0/24** and **eu-west-1b** Availability Zone.

Even though your subnets are labeled **Public**, they are not a public subnet. Do you now why?___

By definition a public subnet must have an Internet Gateway. In the next task you will add an Internet Gateway so that instances in your public subnets can access the Internet. 

## Task 3: Create an Internet Gateway

1.  In the navigation pane on the left, click **Internet Gateways.**
2.  Create an Internet Gateway by configuring the following:
3.  Click **Create Internet Gateway**
4.  **Name tag:** StudentX_IGW
5.  Click **Create** 
6.  Click **Actions** button
7.  Click **Attach to VPC**
8.  Select your VPC and click **Attach**

This will attach the Internet Gateway to your VPC.

Even though you created an Internet Gateway and attached it to your public subnets, you still have to tell instances within your public subnets how to get to the Internet. In the next task, you will add a route to your main VPC route table to tell traffic in your public subnets how to get to the Internet. 

Each subnet in your VPC must be associated with a route table; the table controls the routing for the subnet. A subnet can only be associated with one route table at a time, but you can associate multiple subnets with the same route table. You can also have more then one route table. 

## Task 4: Route Tables and Routes

1.  In the navigation pane on the left, click **Route Tables**.
There is currently one default route table associated with the VPC that you created.

2. Select the route that is associated with your VPC.
3. Click the **Routes** tab.
Notice that there is one route in your route table that is associated with your default network. This route allows traffic from the 10.X.0.0/16 network to pass to other nodes within the network, but it does not allow traffic to go outside of the network. Next, you will create a new route table dedicated for a public subnets and add a route to allow traffic to get to the Internet.

4.  Click **Create route table**, and provide following informations:
     * **Name tag**: StudentX_RTP
     * **VPC**: select your VPC
5.  Click **Create route table** and **Close**
6.  Select a newly created routing table.
7.  Switch to the **Routes** tab and click **Edit routes**.
8.  Click **Add route**.
9.  Click the **Destination** field and enter: **0.0.0.0/0**
10. Click the **Target** field.
11. Click the Internet Gateway and select the gateway that you created earlier in task 3.
12. Click **Save changes** and **Close**.
13. Switch to the **Subnet Associations** tab and click **Edit subnet associations**.
14. Select both Public1 and Public2 subnets and click **Save**.

You have now fully configured your public subnets so that traffic within it can get out to the Internet. In the next Labs you will create a web server in  this public subnet. But before that, you need to control the traffic that is allowed to access your web server. You can do this by creating a security group that only allows HTTP traffic and attaching the security group to your web server.

A security group acts as a virtual firewall for your instance to control inbound and outbound traffic. When you launch an instance in a VPC, you can assign up to five security groups to the instance. Security groups act at the instance level, not the subnet level. Therefore, each instance in a subnet in your VPC could be assigned to a different set of security groups. If you don't specify a particular group at launch time, the instance is automatically assigned to the default security group for the VPC.

## Task 5: Create a Security Group For Your Public Subnet

In this task, you will add a security group so that people can access your web server using HTTP.

1.  In the Navigation pane on the left, click **Security Groups**.
2.  Click **Create security group**.
3.  In the **Basic details** section, configure the following: 
    *  **Security group name:** StudentX_WebServers
    *  **Description**: StudentX Web Servers Security Group
    *  **VPC**: Select your VPC - make sure it's named *StudentX-VPC*   
4.  In the **Inbound rules** section, Click **Add Rule**.
5.  Configure the following:
     * From **Type** list select **HTTP**.
     * From **Source** list select **Anywhere-IPv4**.
6.  Click **Create security group**


## Task 6: Create a Private Subnets For Your Backend MySQL Server

To deploy your RDS database, your VPC must have at least one subnet in at least two Availability Zones in
the region where you want to deploy your DB instance. In this task, you'll create your private subnets for
your soon to be created Amazon RDS instance.

1.   In the navigation menu on the left, click **Subnets**.
2.   Click **Create subnet**.
3.   In the **Create subnet** window configure the following:
   * **VPC**: Select your VPC
   * **Name tag**: StudentX_Private1
   * **Availability Zone**: eu-west-1a
   * **IPv4 CIDR block**: 10.X.10.0/24
   * Click **Create** and **Close**.
4.  Repeat the same steps and create second private subnet. Use **Name tag** StudentX_Private2, CIDR block **10.X.11.0/24** and **eu-west-1b** Availability Zone.


## Task 7: Create a Security Group For Your Backend MySQL Server

Now that your private subnets are configured, you'll want to secure the types of traffic that can access your
MySQL database. In this task, you'll create a security group to only allow MySQL traffic from your public
subnets.

1. In the Navigation pane on the left, click **Security Groups**.
2. Click **Create security group**.
3.  In the **Basic details** section, configure the following: 
   * **Security group name**: StudentX_DB
   * **Description**: StudentX Database Security Group
   * **VPC**: Select your VPC 
4.  In the **Inbound rules** section, Click **Add Rule**.
5.  Configure the following:
     * From **Type** list select **MySQL/Aurora**.
     * From **Source** list select **Custom**.
     * In the next blank box enter: **10.X.0.0/22**.
6.  Click **Create security group**

This rule states that only MySQL traffic coming from your public subnets is allowed to access the database
in the Private subnets.



## END LAB

### Conclusion

In this lab you created a VPC with a two public subnets and two private subnets. AWS provides you with access to multiple Availability Zones at no additional cost. A best practice is to mirror servers across two Availability Zones and then use load balancing and other techniques in order to distribute traffic between them. 

Security group rules can be either very precise or quite loose. You need to ensure that your security groups are as restrictive as possible but not so restrictive that there are unintended side effects. 

<br><br>

<p align="right">&copy; 2022 Welastic Sp. z o.o.<p>
