<img src="https://welastic.pl/wp-content/uploads/2021/10/logo-black.svg" alt="Welastic logo" width="100" align="left">
<br><br>
<br><br>
<br><br>

# Create container based application with AWS ECS Fargate

## LAB Overview

#### This lab leads you through the steps to run a simple Mario Bros game as website. You will use provided docker image and run it using Amazon ECS service.


## Task 1: Create ECR repository.

In thisk task you will create a ECR repository and learn how to push into your new repo. 

1. On the **Services** menu, click IAM.
2. On the left menu click Users.
3. Click on your user name.
4. Go to **Security credencials** tab.
5. Click **Create acces key**.
6. Save yor Access key and Secret key to notepad.


7. On the **Services** menu, click **EC2**. 
8. Go into **Instances**. 
9. Click **Launch Instance**.
10. Choose **Amazon Linux 2 AMI (HVM), SSD Volume Type** and click **Select**
11. Select **Type** **t2.micro** and click **Review and Launch**. 
12. Click **Launch**.
13. If you already have a key pair, select your key pair, if not then create a new one and save it.
14. Click **Launch Instances**.
15. Click **View Instances**.
16. Wait till your instance starts.
17. Select your new instance and click **Actions** and click **Connect**. 
18. Use **SSH client** ssh comand to connect to your machine, example:
```she
ssh -i "studenXpem" ec2-user@ec2-1-222-33-28.eu-west-1.compute.amazonaws.com
```
19. In your EC2 machine run following commands. 

```she
sudo yum install docker -y
```

```she
sudo service docker start
```

```she
sudo chmod 777 /var/run/docker.sock
```
```she
docker pull kaminskypavel/mario
```
20. To configure AWS Credetials run comand bellow.
```she
aws configure
```
21. Paste preveriusly saved **AWS Access Key** and **AWS Secret Access Key**. Set **Default region name** to eu-west-1 and **Default output format** to json.

22. In comand bellow replace X with your student number, and run it to create ECR repository.
```she
aws ecr create-repository --repository-name studentXmario
```

23. Go back to AWS Console, in **Services** click **Elastic Container Service**.
24. In the left menu click **Repositories**
25. Click on your repository name. 
26. Click on **View push commands**. 
27. To retrieve an authentication token and authenticate your Docker client to your registry. Paste first command in your EC2 terminal. 
```she
aws ecr get-login-password --region eu-west-1 | docker login --username AWS --password-stdin 536966783968.dkr.ecr.eu-west-1.amazonaws.com
```
28. Go back to AWS console.
29. To push image to repository you have to tag it. Paste third comand in your EC2 terminal and rename name of docker image as in example bellow. 
```she
docker tag kaminskypavel/mario:latest 536966783968.dkr.ecr.eu-west-1.amazonaws.com/studentXmario:latest
```

30. Copy fourth command to your EC2 terminal, and run this command to push your docker image to AWS ECR repostory. 
```she
docker push 536966783968.dkr.ecr.eu-west-1.amazonaws.com/studentXmario:latest
```

31. Go back to AWS console and check if your image is present in ECR repository. 



## Task 2: Create ECS Cluster
1. In **Services** select Elastic Container Service
2. In the navigation pane on the left, click **Clusters** under Amazon ECS.
3. Click **Create cluster** button.

**Note:** You will find tree options. Two options with classic EC2 machines, where virtual machines will be created in ECS Cluster. Third option (Fargate) is a serverless approach.

4. Select option **Networking only** and **Next step**.
5. Provide all informations: 
   * **Cluster name:** studentXcluster,
   * **Create VPC**: leave unselected.

6. Click **Create** and **View cluster**.

## Task 3: Create a task definition

In this section, you will create and run task that will spin up our docker container with an application.

1.  In the navigation pane on the left, click **Task Definitions**.
2.  Then **Create new Task Definition**, select **FARGATE** and click **Next step**.
3.  Provide all necessary information:

* **Task Definition Name:** StudentX_task
* **Task memory:** 1GB
* **Task CPU:** 0.5 vCPU

4. In **Container Definition** select **Add Container.**
5. Provide following information: 

* **Container name**: studentX_container
* **Image**: 536966783968.dkr.ecr.eu-west-1.amazonaws.com/studentXmario:latest
* **Port mappings:** 8080

6.  Rest of the container configuration leave default and click **Add**. 
7.  Under the task definition windows leave the rest of configuration default and click **Create**. 
8.  Click **View task definition**. 

You can review the configuration. Eventually you can also specify the config in the form of json template.

## Task 4: Run a task

Almost all of the configuration is ready, now you can run your task and test the application.

1.  In the navigation pane on the left, click **Clusters**. 
2.  Select your cluster and switch to **Tasks** tab. 
3.  Click **Run new Task** and provide the following configuration. 

* **Launch type:** Fargate
* **Task definition:** select your task created in previous task.
* **Cluster**: select your cluster 
* **Number of tasks:** 1
* **Cluster VPC:** use your vpc
* **Subnets:** select your Public subnets
* **Security groups**: make sure that port 8080 is opened
* **Auto-assign public IP**: ENABLED

4.  Leave the rest and click **Run Task**
5.  Wait until the service will be in **RUNNING** state 
6.  Click on your task and look for **Public IP** 
7.  Open a new windows in web browser and paste the address with :8080 at the end. 

## END LAB

This is the end of the lab. You can remove an ECS cluster (first stop running task), and terminate EC2 machine. 







<br><br>

<p align="right">&copy; 2022 Welastic Sp. z o.o.<p>
