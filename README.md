# My Portfolio

This is my personal portfolio project that I've created using vanilla JS and PHP. The site is made of 2 parts:  
1 - A portfolio that shows who I am  
2 - A blog where users can post any programming question

## Blog Features
The user to post in the blog section needs to register using his own data. There is no confirmation email but as a security measure the user needs to verify its own phone number through an OTP. Any post is recorded in the system with the IP of the user in case of any problem.  
The administrator can also suspend and/or ban any user violating rules, explicitely written in the "terms & conditions" agreed during the registration process.

## Security Features
The system saves the login session using standard PHP security specs:  
1 - It saves the session in a cypher cookie that allows users to close and open again the browser without the needs to login again  
2 - Each session is also identified by a unique created in the DB after every request

Password are saved using a secure hash function that prevents anyone (the admin himself) to read it clearly.  
The system uses MySQL as DBMS and prevents any type of SQL injections using prepared statements during any DB request.

To avoid spam a Google ReCaptcha V3 is enforced during every DB request

## Real Time Feature
The system uses PHP as a backend but to avoid reloading pages during every request, "fetch" and "ajax (jquery)" are used for a better user experience.

### DISCLAIMER
This site was my first project involving web technologies and uses mainly vanilla programming languages. There is no real time information processing. I'm currently learning REACT and Node.js to update this project in the future.

## License
The project was structured, designed and published online by Simone Bartoli. All rights relating to the contents, functionality and code used are reserved to Simone Bartoli.