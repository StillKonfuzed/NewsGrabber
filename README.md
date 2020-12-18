# NewsGrabber
News Grabber Bot

The news grabber bot grabs news from popular sites and provides the news data in json format.  
Json data includes news title,image,content and a source link to the news.  
Api is available at : https://stillkonfuzed.in.net/NewsGrabber/

# here is a the grabber working live demo  
POST/GET link : https://stillkonfuzed.in.net/NewsGrabber/grabber/gsmarenaNewsGrabberDemo

>The project is server side coded and will remain closed source forever!

># How is this api different then others news grabbers?  
>>Well firstly, you dont have to register to get an api key.  
>>Secondly there is no such api key thing here.  
>>No such request capping here. Enjoy Unlimited requests until server crashes!  
>>The demo version takes 30s to grab the news, but api version will be 30 times faster as it will save grabbed news to database and fetch directly from database.

># RSS vs NEWS GRABBER
>>RSS provides everything except the inner news content from a single site.
>>News Grabber crawls through news links and its inner page contents and saves the full news content in database which are later served from api.
  
># API USAGE RULES  
>>Always give the credit to original site with link to the news (SHOULD REDIRECT). Source link will be mapped to api response by default for every news.  
>>Do not send frequent request. Try to limit requests below 1800 hourly.  

># API ENDPOINTS
>> GET/POST https://stillkonfuzed.in.net/NewsGrabber/grabber/getNews | Gets recent news
>> GET/POST https://stillkonfuzed.in.net/NewsGrabber/grabber/getNewsById?id=1 | Pass  news id as query

># CURRENTLY SUPPORTED SITES  
>>GSMarena.com ~ status -Production (v1)  
>>in.mashable.com ~ status -Beta (v0.6)  
