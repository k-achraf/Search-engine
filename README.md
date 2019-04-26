# TWYNO Search-engine
TWYNO Is a smart search engine based on php langueage It can be used in any website
Its use is easy and simple and achieves accurate results

It brings the search results from the Google search engine and coordinates and displays them

## Note
The most important factors that improve TWYNI search engine results are improving meta tags of your website

# instaling and use
* just download **Search.php** Class and include it in your Search Processing Page
```bash
git clone https://github.com/ashi04/Search-engine.git
```
* Create an object from the class:

  ```php
   $search = new Search();
   ```

* add your website link and search keyword

  ```php
   $search->searchIn('coursdz.com');
   $search->searchFor('رياضيات');
   ```
   
* finally use the function get to get search result array

  ```php
  $result = $search->get();
  ```
  
The result is an array containing arrays. each array contains the title of the search result and its link.

## example

We prepare the search engine like the picture


![prepare image](https://i.ibb.co/6Z7Y2Sv/Capture.png)


we get this result
  

![prepare image](https://i.ibb.co/NNxzS3K/Capture.png)
