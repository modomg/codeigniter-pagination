CodeIgniter Pagination
======================
Simple CodeIgniter library that makes working with pagination easier

Welcome
=======
Here is a simple, but powerful, Pagination Library for CodeIgniter. I have been using this in our projects for years, so it is very well tested.

If you have any requests or changes, please feel free to leave me a request in the "issues" tab for me.

-Chris

Installation
============
1. Drop everything into your application folder (assets folder is just for demo purposes)
2. Use: $config['uri_protocol'] = 'PATH_INFO';
3. Go to the test controller and start working with it. You will have to make your own SQL queries/models. 

Features
========
1. Handles a directory structure or by using the $_GET array. (Ex: domain.com/users/5/ or domain.com/users/?page=5)
2. Directory structure can add a trailing slash or not, which is good for SEO (Ex: domain.com/users/5/ or domain.com/users/5)
3. Page 1 links will not show a pagination number. Search engines will look at domain.com/users/ and domain.com/users/1/ as duplicate content, so the pagination is dropped.
4. Handles regular links or AJAX update links
5. Automatically builds sorting links
6. Handles different HTML tags (li, div, etc) for pagination links and additional HTML/CSS options.
7. Handles additional search/sorting parameters and will add to the $_GET array
8. Page stats output (Ex: Displaying 1 to 25 (of 115 users))

Versions
========
* **v1.2** - Fixed next and previous link css classes, added view all css class, removed unneeded variables
* **v1.1** - Updated copyright, license information, and formatting. Must update views to use: page_stats()
* **v1.03** - Added number formatting to pageStats()
* **v1.02** - Added rel="nofollow" option for links other than page 1