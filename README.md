# NewsGrabber Bot
News Grabber Bot with API. 

>Tired of manually copy, pasting and stealing news from various popular sites to your own news app and website?   
Tired of uploading news and its images from badly coded admin panels ??  
Tired of paying for different news apis and still not getting the full news content??  
Worry no more! News grabber automates all the process and all you need to do is rela.. ok too much advertisement, lets get to point>>

The news grabber bot grabs news from popular sites and provides a cleanly written API.  
API response is always Json data ,  that includes news Title,Image,Content and Source link to the news.  

Noob friendly Api & Docs is available at : https://stillkonfuzed.com/NewsGrabber/

# Here is a quick demo of grabber working live. 
POST/GET link : https://stillkonfuzed.com/NewsGrabber/grabber/gsmarenaNewsGrabberDemo

>~~The project is server side coded and will remain closed source forever!~~
>>Not anymore! The repo is open to everyone.

># How is this api different than others news grabbers?  
>>Well firstly, you dont have to register to get an api key.  
>>Secondly there is no such api key thing here.  
>>No such request capping here. Enjoy Unlimited requests until server crashes!  
>>The demo version takes 30s to grab the news, but api version is 30 times faster as it saves grabbed news to database and fetches directly from database.

># RSS vs NEWS GRABBER
>>RSS provides everything except the inner news content from a single site.  
>>News Grabber solves this as it crawls through news links and its inner page contents and saves the full news content in database which are later served from api.  

># API USAGE RULES  
>>Always give the credit to original site with link to the news (SHOULD REDIRECT). Source link will be mapped to api response by default for every news.  
>>Repeating above point. You MUST include the source link in the news which MUST redirect to source on click.   
>>Do not send frequent request. Try to limit requests below 1800 hourly.  
>>Api is Production ready! Do not abuse.  

># API ENDPOINTS
>> GET/POST https://stillkonfuzed.com/NewsGrabber/grabber/getNews | Gets recent news.  
>> GET/POST https://stillkonfuzed.com/NewsGrabber/grabber/getNewsById?id=1 | Pass  news id as query param.  
>> Get news by category coming soon.

># Pros  
>>No manual copy/pasting of news anymore.  
>>No subscriptions or payments involved.  
>>Free to use upto 2+ years.  
>>Super simple to implement.  
>>No data collected at all not even you IP.   
>>No copyright issues if you include the news source as provided.  
>>Provides more traffic to source webiste where the news was grabbed from.   
>>New sites support added every 2 week.  

># Cons  
>>1 out of 100 image MAY appear broken, for that the api automatically replaces the broken image with placeholder.png  
>>Very limited sites support. (more sites support will come soon).  
>>Some irrelevent news may appear as its a bot not AI. 
>>No news on sundays, most sites post weekly news reviews and blogs on that day. 
>>For precise control over news, you have to contact me. 

># CURRENTLY SUPPORTED SITES  
>>GSMarena.com ~ status -Production (v1)  
>>gadgets360.com ~ status -Beta (v0.6)  

># Apps using this API
>>KK news

>Setup Instruction
>>Clone the project on your xampp's "/htdoc" folder.
>>Import the "SQL/attached-sql-file-with-some-name.sql" from your php admin.
>>Change Database name, username and password according to your imported database in "/grabber/settings.php Line : 15,16,17"
>>Change BaseUrl and Download url in "/grabber/index.php Line : 26 and 29"
>>All Done! Hit "localhost:8080/NewsGrabber/grabber/gsmarenaNewsGrabberDemo" to see a live demo.

