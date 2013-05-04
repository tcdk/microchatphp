# microchatphp #


Small file based chat system developed from 
http://www.phptoys.com/product/micro-chat.html

I'm trying to add a bit more java, expand the text field and and make it usable


## Authentication ##

I've changed the user system to be dependent on auth_basic either with a .htaccess file, which can look something like this:

AuthType Basic
AuthName "Please login"
AuthUserFile  .htpasswd
Require valid-user

And then requires a .htpasswd file. If auth basic isn't used the code will ask for username/password, but will not validate against anything, (everybody will be accepted)

## to-do ##

- [ ] use something more "pushy" for the refresh, instead of reloading every 5 sec
- [ ] inline display of images
- [ ] use my webrtc_snap module for taking photos