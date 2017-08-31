# Modulus\Modulus
## Purpose
This is the main container object and facilitates dependency injection within modulus.
## File Structure
This object requires access to the `config/` directory. This directory should be located in you project root.
## Usage
Because this object handles dependency injection, you cannot and should not inject this object directly anyone in your application.

Instantiate this object the old school way.

    use Modulus\Modulus;
    $modulus = new Modulus($path);
    
The parameter `$path` should be the absolute path to the root of your project. In most cases, this can be set to `__DIR__` as long as the file where the object is being instantiated is in the root of the project.
 
#### get($id)
The get method allows you to retrieve any object declared in the configuration files. 

    $modulus->get("symfony.console.application");
    
* `$id` :: is the id declared for the object in the configuration files.
#### load($resource)
The load method allows you to load a resource xml file that is stored anywhere within your project root.

    $modulus->load("application.xml");
    
* `$resource` :: is the path inside `PATH/TO/PROJECT/config/` where the configuration file is located.
