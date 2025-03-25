# Moodle Chatbot
This repository is for the plugin files for AAC Moodle Chatbot

# Before cloning the Moodle block version, you will need to install the original plugin
Download the zip of the latest version and install the plugin: [https://moodle.org/plugins/local_geniai](url)
Site Administration>Plugins>Install Plugins>Install plugin from the ZIP File

## Next clone the github repository into your mod folder
Ex: C:\xampp\htdocs\MyMoodle\mod

## Pulling changes from the repository to your local repository
Make sure the IDE is connected to GitHub.

### Using Terminal
> git branch #list all the branches a local repository has<br />
> git checkout #get the current state of the branch you are in<br />
> git checkout -b (your branch name) (origin branch) #creates a new branch with the files of the original branch<br />
> git push origin (your branch name) #makes the new branch available remotely<br />
> git merge master #merges changes from the master into feature branch<br />
Make changes to the new branch<br />
> git checkout master #change to the master branch<br />
> git merge --no-ff (your branch name) #create commit object during merge<br />
> git push origin master #push merge changes. In most cases this will be done on the Github so other people can review your work<br />
> git push origin :(your branch name) #deletes the remote branch.<br />


**STEPS FOR MAKING A CLEAN CLONE.**
1. Install Git if you don't have it already.

   Link: https://git-scm.com/downloads

3. Install Git LFS. This is for uploading large files onto github.

   Link: https://git-lfs.com/
   
4. In your command prompt (i.e. Git Bash), use the cd command to travel to the path where you want to clone the repo.

   Ex. cd C:\xampp\htdocs\MyMoodle\mod

5. Clone the repository using the ssh key found in the green code dropdown.

   Ex. git clone PASTE-REPO-SSH-KEY-HERE
   
6. Go inside the repo file using cd
   
7. Create your branch
   
   Ex. git checkout -b jtd5597/first_branch

10. Pull

      Ex. git pull

**STEPS FOR INITIALIZING THE REPO IN YOUR PROJECT FILE IF YOU HAVE ALREADY DONE WORK**

1. Navigate to your project directory using cd command.

2. Initialize git repo

   Ex. git init

4. Add git repo as a remote

   Ex. git remote add origin PASTE-REPO-SSH-KEY-HERE

5. Make your branch and make sure you're switched into it.

   Ex. git checkout -b branch-name

6. Pull the main branch

   Ex. git pull origin main

8. Add your files

   Ex. git add .

   The "." uploads all files. However, git won't add the files that haven't been changed from whatever you're pushing to.

9. Commit your changes

   Ex. git commit -m "Meaningful commit message"

10. Push to the repo

    Ex. git push -u origin jtd5597/first_branch
    
**Debugging using Xdebug**

Navigate to the resource channel of the discord and follow Kartavya's step-by-step instructions of how to install PHP and Xdebug:
[https://discord.com/channels/1328761463774511164/1328771210087235595/1346614587008618588](url)
