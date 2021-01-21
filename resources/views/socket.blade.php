@extends('layouts.app')
@php
    App::setLocale(session('lng'));
@endphp
@section('content')
    <section class="content-header">
        <h1>
            Socket
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- /.box-header -->
                    <div class="box-body">


                        <?php

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'http://' . $sockets[0]->login . ':' . $sockets[0]->pas . '@' . $sockets[0]->ip . ':' . $sockets[0]->port . '/protect/status.xml');
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                        $output = curl_exec($ch);

                        curl_close($ch);

                        preg_match("|<led0>(.*)</led0>|is", $output, $result0);
                        preg_match("|<led1>(.*)</led1>|is", $output, $result1);
                        preg_match("|<led2>(.*)</led2>|is", $output, $result2);
                        preg_match("|<led3>(.*)</led3>|is", $output, $result3);
                        preg_match("|<led4>(.*)</led4>|is", $output, $result4);
                        preg_match("|<led5>(.*)</led5>|is", $output, $result5);
                        preg_match("|<led6>(.*)</led6>|is", $output, $result6);
                        preg_match("|<led7>(.*)</led7>|is", $output, $result7);

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'http://' . $sockets[1]->login . ':' . $sockets[1]->pas . '@' . $sockets[1]->ip . ':' . $sockets[1]->port . '/protect/status.xml');
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                        $output = curl_exec($ch);

                        preg_match("|<led0>(.*)</led0>|is", $output, $result0_1);
                        preg_match("|<led1>(.*)</led1>|is", $output, $result1_1);
                        preg_match("|<led2>(.*)</led2>|is", $output, $result2_1);
                        preg_match("|<led3>(.*)</led3>|is", $output, $result3_1);
                        preg_match("|<led4>(.*)</led4>|is", $output, $result4_1);
                        preg_match("|<led5>(.*)</led5>|is", $output, $result5_1);
                        preg_match("|<led6>(.*)</led6>|is", $output, $result6_1);
                        preg_match("|<led7>(.*)</led7>|is", $output, $result7_1);


                        ?>


                        <div class="row">
                            <div class="col-xs-6">
                                <h2>Socket №1</h2>
                                <table style="border: 1px solid silver; width: 100%;" id="sock">
                                    <tr>
                                        <td><b>Реле 0:</b></td>
                                        <td><?php if (isset($result0['1']) && $result0['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 1:</b></td>
                                        <td><?php if (isset($result1['1']) && $result1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 2:</b></td>
                                        <td><?php if (isset($result2['1']) && $result2['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 3:</b></td>
                                        <td><?php if (isset($result3['1']) && $result3['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 4:</b></td>
                                        <td><?php if (isset($result4['1']) && $result4['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 5:</b></td>
                                        <td><?php if (isset($result5['1']) && $result5['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 6:</b></td>
                                        <td><?php if (isset($result6['1']) && $result6['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 7:</b></td>
                                        <td><?php if (isset($result7['1']) && $result7['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                </table>

                                <h4>Настроить:</h4>
                                <form method="POST" action="{{action('SocketController@edit')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input type="text" name="id" value="1" class="form-control" style="display: none;"
                                       required>
                                <div class="col-xs-12">
                                    <label>IP</label>
                                    <input type="text" name="ip" value="{{$sockets[0]->ip}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Порт</label>
                                    <input type="text" name="port" value="{{$sockets[0]->port}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Логин</label>
                                    <input type="text" name="login" value="{{$sockets[0]->login}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Пароль</label>
                                    <input type="text" name="pas" value="{{$sockets[0]->pas}}" required
                                           class="form-control" required>
                                </div>

                                <input type="submit" style="color: white; width: 300px; margin: 20px;"
                                       class="btn btn-success btn-block btn-default btn-lg" value="Изменить"></form>
                            </div>


                            <div class="col-xs-6">
                                <h2> Socket №2</h2>

                                <table style="border: 1px solid silver; width: 100%;" id="sock">
                                    <tr>
                                        <td><b>Реле 0:</b></td>
                                        <td><?php if (isset($result0_1['1']) && $result0_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 1:</b></td>
                                        <td><?php if (isset($result1_1['1']) && $result1_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 2:</b></td>
                                        <td><?php if (isset($result2_1['1']) && $result2_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 3:</b></td>
                                        <td><?php if (isset($result3_1['1']) && $result3_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 4:</b></td>
                                        <td><?php if (isset($result4_1['1']) && $result4_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 5:</b></td>
                                        <td><?php if (isset($result5_1['1']) && $result5_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 6:</b></td>
                                        <td><?php if (isset($result6_1['1']) && $result6_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Реле 7:</b></td>
                                        <td><?php if (isset($result7_1['1']) && $result7_1['1'] == 0) {
                                                echo '<img src="/images/Button_On.png">';
                                            } else {
                                                echo '<img src="/images/Button_Off.png">';
                                            } ?></td>
                                    </tr>
                                </table>

                                <h4>Настроить:</h4>
                                <form method="POST" action="{{action('SocketController@edit')}}"
                                      enctype="multipart/form-data"/>
                                {{ csrf_field() }}

                                <input type="text" name="id" value="2" class="form-control" style="display: none;"
                                       required>
                                <div class="col-xs-12">
                                    <label>IP</label>
                                    <input type="text" name="ip" value="{{$sockets[1]->ip}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Порт</label>
                                    <input type="text" name="port" value="{{$sockets[1]->port}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Логин</label>
                                    <input type="text" name="login" value="{{$sockets[1]->login}}" required
                                           class="form-control" required>
                                </div>

                                <div class="col-xs-12">
                                    <label>Пароль</label>
                                    <input type="text" name="pas" value="{{$sockets[1]->pas}}" required
                                           class="form-control" required>
                                </div>

                                <input type="submit" style="color: white; width: 300px; margin: 20px;"
                                       class="btn btn-success btn-block btn-default btn-lg" value="Изменить"></form>
                            </div>


                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->

{{--
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('site.add')</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{action('CategoryStockController@store')}}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="text" name="title" class="form-control" value="{{ $stock->title ?? '' }}"
                               required/>
                        <br> <input type='submit' class="btn btn-primary active"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-default2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('site.add')</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{action('CategoryStockController@edit')}}"
                          enctype="multipart/form-data"/>
                    {{ csrf_field() }}
                    <input type="text" style="display: none" name="id" class="form-control idcatecory" value="1"
                           required/>
                    <input type="text" name="title" class="form-control titlecatecory" value="2" required/>
                    <br> <input type='submit' class="btn btn-primary active"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
--}}


@endsection
