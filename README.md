# Modulus Console Framework

## What is Modulus?
Console Application Boilerplate Project. Can be used to build custom console application.

## How do I create my own Modulus project?
It is as simple running:

    composer create-project wesleywmd/modulus myproject
    
Where `myproject` is the directory you want to create your Modulus project.

Now that you have your own Modulus project created, check out the [Tutorials Section](docs/tutorials.md).

## How do I use my Modulus project?
First lets run the application. From the root of your modulus project, run this bash command.

    bin/modulus
    
You should see a base console application output message.

Modulus has dependency cache. So periodically during development, you may want to flush this cache. You can run the follow command to do so.

    bin/flushcache
    
You can also switch the cache to debug mode. In your `etc/system.yaml` add the follow config.

    is_debug: true
    
This will allow modulus to track for changes and update the cache if needed. It is not recommended to run Modulus with debug enabled in production. It is only for development use.
    
## How do I include a Modulus module?
Yes! Modulus is extendable! You can include modules in modulus by composer requiring them into your modulus project. As long as the module is configured correctly, it should automatically be registered in Modulus for you.

More details for creating your own Modulus module can be found [here](docs/create-new-module.md).

You want to include a composer library that is not a modulus module? No problem. you can set up how ever namespaces to autowire into your project. 

Details for this can be found [here](docs/tutorials/autowiring.md).

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning
We have not yet implemented versioning on this repository.

## Authors
- **Wesley Guthrie** - *Initial work* - therealwesleywmd@gmail.com

See also the list of [contributors](https://github.com/wesleywmd/modulus/graphs/contributors) who participated in this project.

## License
This project is licensed under the [MIT](https://choosealicense.com/licenses/mit/) license.

    


