<!--
 API Documentation HTML Template  - 1.0.1
 Copyright © 2016 Florian Nicolas
 Licensed under the MIT license.
 https://github.com/ticlekiwi/API-Documentation-HTML-Template
 !-->
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <title>API - Documentation</title>
    <meta name="description" content="">
    <meta name="author" content="ticlekiwi">

    <meta http-equiv="cleartype" content="on">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/API-Doc/css/hightlightjs-dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,500|Source+Code+Pro:300" rel="stylesheet">
    <link rel="stylesheet" href="/API-Doc/css/style.css" media="all">
    <script>hljs.initHighlightingOnLoad();</script>
</head>

<body>
<div class="left-menu">
    <div class="content-logo">
        <img alt="platform by Emily van den Heever from the Noun Project" title="platform by Emily van den Heever from the Noun Project" src="images/logo.png" height="32" />
        <span>API Documentation</span>
        <div class="lang">
            <a href="#" onclick="return false;" class="active">EN</a>
            <a href="/helpapi">RU</a>
        </div>
    </div>
    <div class="content-menu">
        <ul>
            <li class="scroll-to-link active" data-target="listTable">
                <a>List of tables </a>
            </li>
            <li class="scroll-to-link" data-target="bronir">
                <a>Occupied tables</a>
            </li>
            <li class="scroll-to-link" data-target="Bron">
                <a>Table reservation</a>
            </li>
        </ul>
    </div>
</div>
<div class="content-page">
    <div class="content">
        <div class="overflow-hidden content-section active" id="listTable">
            <h2 >List of billiard tables</h2>
            <p>
                To get tables of a specific billiard room you need to make a GET call to the following url:<br>
                <code class="higlighted">https://p.bb-crm.com/api/listtables</code>
            </p>

            <pre>
                <code class="json">
                Result example
                 [
                     {
                      "table": 16,
                      "title": "№1 пул"
                     },
                     {
                      "table": 17,
                       "title": "№2 пул"
                    },
                 ]
                </code>
            </pre>

            <h4>QUERY PARAMETERS</h4>
            <table>
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>api_token</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                <!--
                <tr>
                    <td>billiardroom</td>
                    <td>
                        Number
                    </td>
                    <td>
                        Billiard room
                    </td>
                </tr>
                -->

                </tbody>
            </table>

            <h4>Answer PARAMETERS</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                <tbody>

                <tr>
                    <td>table</td>
                    <td>Number</td>
                    <td>ID Table</td>
                </tr>
                <tr>
                    <td>title</td>
                    <td>String</td>
                    <td>Table name</td>
                </tr>

                </tbody>
            </table>

        </div>

        <div class="overflow-hidden content-section" id="bronir">
            <h2 id="get-characters">Receiving the booked tables for the next 7 days</h2>
            <p>
                To get the list of tables, you need to make a GET call to the following URL:<br>
                <code class="higlighted">https://p.bb-crm.com/api/busytables</code>
            </p>
            <br>
            <pre>
                <code class="json">
                Result example succes :
                 {
                    "error": [],
                    "succes": [
                               {
                                  "date_start": "2020-10-01 15:00:00",
                                  "date_end": "2020-10-01 16:00:00",
                                   "table": 17
                               },
                               {
                                "date_start": "2020-10-01 15:00:00",
                                 "date_end": "2020-10-01 16:00:00",
                                  "table": 17
                                }
                               ]
                    }

                </code>
            </pre>
            <h4>QUERY PARAMETERS</h4>
            <table>
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>api_token</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                <!--
                <tr>
                    <td>billiardroom</td>
                    <td>
                        Number
                    </td>
                    <td>
                        Billiard room
                    </td>
                </tr>
                -->
                </tbody>
            </table>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>

                <tr>
                    <td>error</td>
                    <td>Array</td>
                    <td>Parameter not specified</td>
                </tr>
                <tr>
                    <td>succes</td>
                    <td>Array</td>
                    <td>
                        "date_start": "2020-10-01 15:00:00", - table start <br>
                        "date_end": "2020-10-01 16:00:00",    - table end<br>
                        "table": 17                           - id table
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>
        </div>

        <div class="overflow-hidden content-section" id="Bron">
            <h2 >Table reservation</h2>
            <p>
                To book a table, you need to make a POST call to the following url:<br>
                <code class="higlighted">https://p.bb-crm.com/api/tobooktables</code>
            </p>
            <br>
            <pre>
                <code class="json">
                Result example not error:
                 {
                    "error":[],
                    "succes":41
                    }
                </code>
                <code class="json">
                Result example with error:
                     {"error":
                            {
                              "date_start":false,
                               "date_end":false,
                               "phone":false,
                                "table":false,
{{--                                "billiardroom":false--}}
                             },
                       "succes":[]
                    }
                </code>
            </pre>
            <h4>QUERY PARAMETERS</h4>
            <table>
                <thead>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>api_token</td>
                    <td>String</td>
                    <td>Your API key.</td>
                </tr>
                <tr>
                    <td>date_start</td>
                    <td>
                        String (2020-09-28 15:00)
                    </td>
                    <td>
                        Start booking
                    </td>
                </tr>

                <tr>
                    <td>date_end</td>
                    <td>
                        String (2020-09-28 15:00)
                    </td>
                    <td>
                        End of booking
                    </td>
                </tr>

                <tr>
                    <td>phone</td>
                    <td>
                        String (0678855393)
                    </td>
                    <td>
                       Phone
                    </td>
                </tr>

                <tr>
                    <td>email</td>
                    <td>
                        String
                    </td>
                    <td>
                        Email
                    </td>
                </tr>

                <tr>
                    <td>table</td>
                    <td>
                        Number
                    </td>
                    <td>
                        Table number
                    </td>
                </tr>

                <tr>
                    <td>status</td>
                    <td>
                        Number
                    </td>
                    <td>
                        2- paid<br>
                        1 - not paid
                    </td>
                </tr>

                <!--
                <tr>
                    <td>billiardroom</td>
                    <td>
                        Number
                    </td>
                    <td>
                        Billiard room
                    </td>
                </tr>
                -->

                </tbody>
            </table>
            <h4>Answer PARAMETERS</h4>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
                <tbody>

                <tr>
                    <td>succes</td>
                    <td>Number</td>
                    <td>ID Armor</td>
                </tr>
                <tr>
                    <td>error</td>
                    <td>Array</td>
                    <td>Array containing blank data</td>
                </tr>

                </tbody>

            </table>
        </div>
    </div>
    <div class="content-code">


    </div>
</div>
<!-- Github Corner Ribbon - to remove (Ribbon created with : http://tholman.com/github-corners/ )-->
<a href="https://github.com/ticlekiwi/API-Documentation-HTML-Template" class="github-corner" aria-label="View source on Github" title="View source on Github"><svg width="80" height="80" viewBox="0 0 250 250" style="z-index:99999; fill:#70B7FD; color:#fff; position: fixed; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a><style>.github-corner:hover .octo-arm{animation:octocat-wave 560ms ease-in-out}@keyframes octocat-wave{0%,100%{transform:rotate(0)}20%,60%{transform:rotate(-25deg)}40%,80%{transform:rotate(10deg)}}@media (max-width:500px){.github-corner:hover .octo-arm{animation:none}.github-corner .octo-arm{animation:octocat-wave 560ms ease-in-out}}</style>
<!-- END Github Corner Ribbon - to remove -->
<script src="/API-Doc/js/script.js"></script>
</body>
</html>

