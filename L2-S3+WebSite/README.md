<img src="https://welastic.pl/wp-content/uploads/2021/10/logo-black.svg" alt="Welastic logo" width="100" align="left">
<br><br>
<br><br>
<br><br>

# Amazon S3 and CloudFront

## LAB Overview

#### This lab introduces you to Amazon S3 and Amazon CloudFront. In this lab you will create an Amazon CloudFront distribution that will use a CloudFront domain name in the url to distribute a publicly accessible website stored in an Amazon S3 bucket.

## Task 1: Creating a static S3 Web Site

1. In the AWS Management Console, on the **Services** menu, click **S3**.
2. Click *+Create bucket**
3. Paste the name: **studnetX-bsh-labs**
4. Select Region: **EU (Ireland)**
5. Select **ACLs enabled**
6. Click **Create bucket**.
7. Find your bucket and click on its name.
8. Select **Properties**.
9. Scroll down to the last section, select **Static website hosting**.
10. Select **Static website hosting ---> Enable**.
11. **Hosting type**: Host a static website.
12. Enter *index.html* as **Index document**.
13. Save changes --> **Properties** and scroll down.
14. Copy the **Endpoint** url for your site.
15. Click **Objects** on top menu.
16. Click **Upload**.
17. Click **Add files**.
18. Upload file *index.html* from *site* folder.
19. Try opening the copied link in your browser.

Does it work? Apply the policy allowing everyone to get files if necessary.

## Task 2: Applying Bucket permission

1.  Select **Permissions**.
2.  Click **Block public asscess**.
3.  Click **Edit**.
4.  Uncheck **Block all public access**.
5.  Click **Save**.
6.  Enter *confirm* and click **Confirm**.
7.  Select **Objects**.
8.  Select **index.html**
9.  From **Action** select **Make public using ACL**
10. Click **Make public**
11. Go back to your browser and check if your site is working.


## Task 3: Creating an Amazon CloudFront Web Distribution

Now you will create an Amazon CloudFront web distribution that distributes the file stored in the publicly accessible Amazon S3 bucket.

1.  In the AWS Management Console homepage, click on **Services** at the top of the console and then click **CloudFront** to open the Amazon CloudFront console.
2.  Click **Create Distribution**.
3.  On the next page, click in the **Origin Domain** box and select your Amazon S3 bucket that contains a publicly accessible files that you want to distribute.
4.  Set **Origin Access** to **Legacy access identities**.
5.  Select **Create new OAI** for **Origin Access Identity**.
6.  Confirm by clicking **Create**
7.  Select **Yes, update the bucket policy** for **Bucket Policy**.
8.  Select **Redirect HTTP to HTTPS** for **Viewer Protocol Policy**.
9.  In **Settings** area set **Price Class** to **Use only North America and Europe**.
10. Set **Default Root Object** to *index.html*.
11. Accept the default values for the rest of the parameters on this page, and click **Create Distribution**.

The **Last modified** column shows **Deploying** for your distribution on the next page. After Amazon CloudFront has created your distribution, the value of the **Last modified**  column for your distribution will change to **today date** and it will then be ready to process requests. This should take around 15-20 minutes.

The domain name that Amazon CloudFront assigns to your distribution appears in the list of distributions. It will normally look something like &quot;dm2afjy05tegj.cloudfront.net.&quot; (It also appears on the **General** tab for a selected distribution.)

## Task 4: Using the Amazon CloudFront Web Distribution

Amazon CloudFront now knows where your Amazon S3 origin server is, and you know the domain name associated with the distribution. You can create a link to your Amazon S3 bucket content with that domain name, and have Amazon CloudFront serve it.

1.  Go back to your website bucket.
2.  Try opening your website using S3 url. Does It work?
3.  Look into the bucket policy that was updated by CloudFront distribution. The bucket policy should look like:

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "2",
            "Effect": "Allow",
            "Principal": {
                "AWS": "arn:aws:iam::cloudfront:user/CloudFront Origin Access Identity E1X56ER0YQB7O5"
            },
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::your-bucket-name/*"
        }
    ]
}
```
4.  Next, open the website using the CloudFront url.
5.  Click your object **index.html** and check **Permisions**. 
6.  Remove the part that grant public access for everyone. 
7.  Try opening your website using S3 url. Does It work now?
8.  Check if you can open your site using CloudFront URL.


## Task 5. Clean up.

1.  In the AWS Management Console homepage, click on **Services** at the top of the console and then click **CloudFront** to open the Amazon CloudFront console.
2.  Find your distribution and check the checkbox left to it.
3.  Click **Disable**.
4.  Confirm  by clicking **Disable** again.
5.  Click **Close** and wait until distribution status changes to **date**.
6.  Select checkbox next to your distribution and click **Delete**.
7.  Confirm by clicking **Delete** again.



## END LAB

<br><br>

<p align="right">&copy; 2022 Welastic Sp. z o.o.<p>
