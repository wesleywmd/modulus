# Modulus Console Framework
Simplified symfony console micro framework with some goodies baked in.

## Installation 
Since Modulus is not available in Packagist yet, you must first add the respository to your project's composer file.

    {
      ...
       
      "repositories": [
        ...
         
        {
          "type": "vcs",
          "url": "https://github.com/wesleywmd/modulus.git"
        }
        
        ...
      ]
      
      ...
    }

Then you can include the package in your project. Since Modulus is still in development, there are no tagged versions as of yet. Please require the package at the master branch.

    composer require wesleywmd/modulus:dev-master

## What does Modulus include?
Modulus will automatically include a number of dependencies to start. 

    "symfony/config": "^3.3"
    "symfony/console": "^3.3"
    "symfony/dependency-injection": "^3.3"
    
Modulus also includes a number of its own objects as well.
* [Modulus\Modulus](docs/Modulus.md) : Facilitates the dependency injection container 
* **_Modulus\Application_** : {Deprecated - This object will be removed in a future release}  


    


