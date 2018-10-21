<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title></title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="title m-b-md">
                Rhino Project
            </div>

            <form method="POST" action="/uploadXls" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="xlsFile"/>
                <button type="submit">Import Users</button>
            </form>
        </div>

        <br/>

        @if (isset($result) && $result == 'fail')
            <div class="content" id="error">
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if(isset($users) && count($users) > 0)
        <div>
            <table align="center" style="margin: 0px auto;">
                <th align="left">&#x2714;</th>
                <th align="left">First Name</th>
                <th align="left">Surname</th>
                <th align="left">Email</th>
                <th align="left">Contact</th>
                <th align="left">Date Joined</th>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" name="handled" {{ $user->validateAll() ? 'checked' : '' }}>
                        </td>

                        <td
                                style="color:{{$user->getTextStyle('firstName')}};">
                                {{ $user->getFirstNameForPrinting() }}
                        </td>

                        <td
                                style="color:{{$user->getTextStyle('surname')}};">
                                {{ $user->getSurnameForPrinting() }}
                        </td>

                        <td
                                style="color:{{$user->getTextStyle('email')}};">
                                {{ $user->getEmailForPrinting() }}
                        </td>

                        <td
                                style="color:{{$user->getTextStyle('contact')}};">
                                {{ $user->getContactForPrinting() }}
                        </td>

                        <td
                                style="color:{{$user->getTextStyle('joinDate')}};">
                                {{ $user->getJoinDateForPrinting() }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif

    </body>
</html>
