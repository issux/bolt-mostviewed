MostViewed
=======================

An extension to show most viewed contenttype on your bolt.cm site 


Installation
=======================

Download and extract the extension to a directory called MostViewed in your Bolt extension directory.

Create the database tables manually by using the queries below.

Copy `config.yml.dist` to `config.yml` in the same directory.


Usage
=======================

If you want to show most viewed conttentype in sidebar(_aside.twig), add the following code: 

    <h3>Most viewed posts</h3>
    <ul>
        {% for record in mostviewedget('posts') %}
        <li>
            <a href="{{ record.link }}" title="See post '{{ record.title }}'" itemprop="url">
                {{ record.title }}
            </a>                    
        </li>
        {% endfor %}
    </ul>

And you must add the following code in template used to show contenttype detail:

`{{ mostviewedupdate(record.id, 'contenttype') }}`

For example:

`{{ mostviewedupdate(record.id, 'posts') }}`


Database
=======================

You need to manually create the db tables:

    CREATE TABLE IF NOT EXISTS `bolt_most_viewed` (
      `contenttypeid` int(11) NOT NULL,
      `contenttype` varchar(255) NOT NULL,
      `views` int(11) NOT NULL,
      UNIQUE KEY `contenttypeid` (`contenttypeid`,`contenttype`),
      KEY `views` (`views`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
