<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $programming = Course::where('category', 'Programming')->first();
        $networking = Course::where('category', 'Networking')->first();
        $database = Course::where('category', 'Database')->first();

        $programmingQuestions = [
            ['What does HTML stand for?', ['Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyper Transfer Markup Language'], 0, 'HTML is the standard markup language for creating web pages.'],
            ['Which data structure uses FIFO (First In, First Out)?', ['Stack', 'Queue', 'Array', 'Tree'], 1, 'A queue operates on FIFO principle, like a line of people.'],
            ['In object-oriented programming, what is encapsulation?', ['Hiding internal state', 'Inheriting properties', 'Overloading methods', 'Abstracting interfaces'], 0, 'Encapsulation bundles data and methods while hiding internal state.'],
            ['What is the time complexity of binary search?', ['O(n)', 'O(log n)', 'O(n²)', 'O(n log n)'], 1, 'Binary search halves the search space each iteration, giving O(log n).'],
            ['Which of the following is a dynamically-typed language?', ['Java', 'C++', 'Python', 'Rust'], 2, 'Python determines variable types at runtime, unlike statically-typed languages.'],
            ['What does the "this" keyword refer to in most OOP languages?', ['Current instance', 'Parent class', 'Global object', 'Static method'], 0, '"this" refers to the current instance of the class.'],
            ['Which sorting algorithm has the best average-case time complexity?', ['Bubble Sort', 'Insertion Sort', 'Merge Sort', 'Selection Sort'], 2, 'Merge Sort has O(n log n) average time complexity.'],
            ['What is a constructor in OOP?', ['A special method called when an object is instantiated', 'A method that destroys an object', 'A static utility method', 'A method that returns values'], 0, 'A constructor initializes objects when they are created.'],
            ['Which symbol is used for single-line comments in JavaScript?', ['//', '/*', '#', '--'], 0, '// is used for single-line comments in JavaScript.'],
            ['What is the primary purpose of an API?', ['To enable communication between different software systems', 'To store data in a database', 'To render user interfaces', 'To compile source code'], 0, 'APIs allow different software systems to communicate with each other.'],
            ['What is the difference between "==" and "===" in JavaScript?', ['Type coercion vs strict equality', 'Assignment vs comparison', 'String vs number comparison', 'There is no difference'], 0, '== performs type coercion, === requires both value and type to match.'],
            ['Which of the following is NOT a primitive data type in Java?', ['int', 'boolean', 'String', 'char'], 2, 'String is a reference type (class) in Java, not a primitive.'],
            ['What does SQL stand for?', ['Structured Query Language', 'Simple Query Language', 'Standard Query Logic', 'Sequential Query Language'], 0, 'SQL is the standard language for relational database management.'],
            ['In version control, what does "git commit" do?', ['Saves changes to the local repository', 'Uploads code to a server', 'Creates a new branch', 'Merges two branches'], 0, 'git commit saves staged changes to the local repository.'],
            ['What is a recursive function?', ['A function that calls itself', 'A function that never returns', 'A function with no parameters', 'A function that calls another function'], 0, 'A recursive function calls itself to solve smaller subproblems.'],
            ['Which data type is used to store a single character in C?', ['char', 'string', 'varchar', 'text'], 0, 'The char type stores a single character in C.'],
            ['What is the purpose of an index in a database?', ['Speed up query performance', 'Store large objects', 'Encrypt data', 'Backup data'], 0, 'Indexes speed up data retrieval operations in databases.'],
            ['What does CSS stand for?', ['Cascading Style Sheets', 'Computer Style Sheets', 'Creative Style System', 'Colorful Style Sheets'], 0, 'CSS is used to style and layout web pages.'],
            ['Which loop is guaranteed to execute at least once?', ['do-while', 'while', 'for', 'foreach'], 0, 'A do-while loop checks the condition after executing the body.'],
            ['What is an array?', ['A collection of elements stored in contiguous memory locations', 'A function that returns multiple values', 'A type of loop', 'A database table'], 0, 'Arrays store elements in contiguous memory locations.'],
        ];

        $networkingQuestions = [
            ['What does IP stand for?', ['Internet Protocol', 'Internal Process', 'Integrated Platform', 'Information Provider'], 0, 'IP is the principal communications protocol for the Internet.'],
            ['Which device is used to connect different networks together?', ['Router', 'Switch', 'Hub', 'Modem'], 0, 'Routers connect different networks and forward data between them.'],
            ['What is the full form of DNS?', ['Domain Name System', 'Digital Network Service', 'Data Network Security', 'Dynamic Name Server'], 0, 'DNS translates domain names to IP addresses.'],
            ['Which protocol is used for secure web communication?', ['HTTPS', 'HTTP', 'FTP', 'SMTP'], 0, 'HTTPS encrypts communication using TLS/SSL.'],
            ['What does LAN stand for?', ['Local Area Network', 'Large Area Network', 'Long Access Network', 'Logical Address Network'], 0, 'A LAN is a network confined to a small geographic area.'],
            ['Which layer of the OSI model handles routing?', ['Network Layer', 'Transport Layer', 'Application Layer', 'Data Link Layer'], 0, 'The Network Layer (Layer 3) handles routing and forwarding.'],
            ['What is a MAC address?', ['A unique hardware identifier for network interfaces', 'An IP address assigned by DHCP', 'A domain name', 'A type of network cable'], 0, 'MAC addresses uniquely identify network interfaces at the hardware level.'],
            ['Which protocol is used to send email?', ['SMTP', 'HTTP', 'FTP', 'TCP'], 0, 'SMTP (Simple Mail Transfer Protocol) is used for sending email.'],
            ['What does VPN stand for?', ['Virtual Private Network', 'Very Personal Network', 'Visual Processing Node', 'Verified Public Network'], 0, 'A VPN creates an encrypted tunnel for secure remote access.'],
            ['What is the default port for HTTP?', ['80', '443', '21', '22'], 0, 'HTTP uses port 80 by default.'],
            ['Which topology connects all devices to a single central cable?', ['Bus topology', 'Star topology', 'Ring topology', 'Mesh topology'], 0, 'In bus topology, all devices connect to a single backbone cable.'],
            ['What is a firewall used for?', ['To block unauthorized access to a network', 'To speed up internet connections', 'To store network passwords', 'To assign IP addresses'], 0, 'Firewalls filter network traffic to prevent unauthorized access.'],
            ['What does TCP stand for?', ['Transmission Control Protocol', 'Transfer Communication Protocol', 'Transport Control Process', 'Terminal Connection Protocol'], 0, 'TCP provides reliable, ordered delivery of data streams.'],
            ['Which IP address is a loopback address?', ['127.0.0.1', '192.168.0.1', '10.0.0.1', '0.0.0.0'], 0, '127.0.0.1 is the loopback address that points to the local machine.'],
            ['What is a subnet mask used for?', ['To determine which part of an IP address is the network and which is the host', 'To encrypt network traffic', 'To assign domain names', 'To manage network cables'], 0, 'Subnet masks separate the network and host portions of an IP address.'],
            ['Which wireless standard operates at 5 GHz?', ['802.11ac', '802.11b', '802.11g', '802.11n'], 0, '802.11ac operates on the 5 GHz frequency band.'],
            ['What does DHCP do?', ['Automatically assigns IP addresses to devices on a network', 'Encrypts network traffic', 'Routes packets between networks', 'Manages domain names'], 0, 'DHCP dynamically assigns IP addresses and network configuration.'],
            ['Which protocol is used for file transfer?', ['FTP', 'HTTP', 'SMTP', 'SNMP'], 0, 'FTP (File Transfer Protocol) is designed for file transfers.'],
            ['What is the purpose of NAT?', ['To translate private IP addresses to public IP addresses', 'To encrypt network traffic', 'To assign domain names', 'To manage wireless connections'], 0, 'NAT allows multiple devices to share a single public IP address.'],
            ['What does QoS stand for in networking?', ['Quality of Service', 'Query of System', 'Quick Online Service', 'Quantitative Output Standard'], 0, 'QoS manages network resources to ensure performance for critical traffic.'],
        ];

        $databaseQuestions = [
            ['What does SQL stand for?', ['Structured Query Language', 'Simple Query Language', 'Standard Query Logic', 'Sequential Query Language'], 0, 'SQL is the standard language for relational database management.'],
            ['Which SQL statement is used to retrieve data?', ['SELECT', 'GET', 'FETCH', 'RETRIEVE'], 0, 'SELECT is used to query data from a database.'],
            ['What is a primary key?', ['A unique identifier for each row in a table', 'A foreign key reference', 'A type of index', 'A column that can be NULL'], 0, 'A primary key uniquely identifies each record in a table.'],
            ['What is normalization?', ['The process of organizing data to reduce redundancy', 'The process of encrypting data', 'The process of backing up data', 'The process of indexing data'], 0, 'Normalization reduces data redundancy and improves integrity.'],
            ['Which JOIN returns all records when there is a match in either table?', ['FULL OUTER JOIN', 'INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN'], 0, 'FULL OUTER JOIN returns all rows when there is a match in either table.'],
            ['What does ACID stand for in databases?', ['Atomicity, Consistency, Isolation, Durability', 'Accuracy, Consistency, Integrity, Durability', 'Atomicity, Consistency, Integrity, Data', 'Access, Control, Isolation, Data'], 0, 'ACID properties ensure reliable database transactions.'],
            ['Which of the following is a NoSQL database?', ['MongoDB', 'MySQL', 'PostgreSQL', 'Oracle'], 0, 'MongoDB is a document-oriented NoSQL database.'],
            ['What is a foreign key?', ['A field that references the primary key of another table', 'A unique identifier for a row', 'An indexed column', 'A column with a default value'], 0, 'A foreign key links two tables together.'],
            ['What does the GROUP BY clause do?', ['Groups rows that have the same values into summary rows', 'Orders the result set', 'Filters rows based on a condition', 'Joins two tables'], 0, 'GROUP BY groups rows for aggregate calculations.'],
            ['What is an index in a database?', ['A data structure that improves the speed of data retrieval', 'A foreign key constraint', 'A type of join', 'A backup of the database'], 0, 'Indexes speed up data retrieval operations.'],
            ['Which SQL statement is used to delete a table?', ['DROP', 'DELETE', 'REMOVE', 'CLEAR'], 0, 'DROP removes an entire table from the database.'],
            ['What is a transaction in a database?', ['A unit of work performed against a database', 'A type of query', 'A database backup', 'A table relationship'], 0, 'A transaction is a single unit of database operations.'],
            ['What does the HAVING clause do?', ['Filters groups after GROUP BY is applied', 'Filters rows before GROUP BY', 'Orders the result', 'Joins tables'], 0, 'HAVING filters grouped results, similar to WHERE for individual rows.'],
            ['What is denormalization?', ['The process of adding redundancy to improve read performance', 'The process of removing all redundancy', 'The process of encrypting data', 'The process of creating indexes'], 0, 'Denormalization adds controlled redundancy for faster reads.'],
            ['Which SQL function returns the number of rows?', ['COUNT()', 'SUM()', 'AVG()', 'MAX()'], 0, 'COUNT() returns the number of rows in a result set.'],
            ['What is a view in SQL?', ['A virtual table based on the result of a SELECT query', 'A physical copy of a table', 'A type of index', 'A database backup'], 0, 'A view is a stored query that acts like a virtual table.'],
            ['What does the UNION operator do?', ['Combines the result sets of two or more SELECT statements', 'Joins two tables', 'Filters duplicate rows', 'Orders the result set'], 0, 'UNION combines results from multiple SELECT queries.'],
            ['Which constraint ensures that all values in a column are unique?', ['UNIQUE', 'PRIMARY KEY', 'FOREIGN KEY', 'CHECK'], 0, 'UNIQUE ensures all values in a column are distinct.'],
            ['What is a stored procedure?', ['A prepared SQL code that can be saved and reused', 'A type of function', 'A database trigger', 'An index'], 0, 'Stored procedures are reusable SQL code blocks.'],
            ['Which NoSQL data model stores data as key-value pairs?', ['Redis', 'MongoDB', 'Cassandra', 'Neo4j'], 0, 'Redis is a key-value store NoSQL database.'],
        ];

        foreach ($programmingQuestions as $q) {
            Question::create([
                'course_id' => $programming->id,
                'question_text' => $q[0],
                'options' => $q[1],
                'correct_answer' => $q[2],
                'explanation' => $q[3],
                'points' => 50,
            ]);
        }

        foreach ($networkingQuestions as $q) {
            Question::create([
                'course_id' => $networking->id,
                'question_text' => $q[0],
                'options' => $q[1],
                'correct_answer' => $q[2],
                'explanation' => $q[3],
                'points' => 50,
            ]);
        }

        foreach ($databaseQuestions as $q) {
            Question::create([
                'course_id' => $database->id,
                'question_text' => $q[0],
                'options' => $q[1],
                'correct_answer' => $q[2],
                'explanation' => $q[3],
                'points' => 50,
            ]);
        }
    }
}
