<?php

/* Servidor: localhost */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Development Local';
$cfg['Servers'][$i]['host'] = 'mariadb';
$cfg['Servers'][$i]['port'] = '3306';
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['AllowNoPassword'] = true;

/* Servidor: Servidor Remoto */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Production';
$cfg['Servers'][$i]['host'] = 'volleytrack.cotjftyqknlg.us-east-1.rds.amazonaws.com';
$cfg['Servers'][$i]['port'] = '3306'; // ou outra porta se necessário
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['auth_type'] = 'cookie';

/* Servidor: Servidor Remoto */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Production Migration';
$cfg['Servers'][$i]['host'] = 'volleytrack.c70aw8qy813u.us-east-1.rds.amazonaws.com';
$cfg['Servers'][$i]['port'] = '3306'; // ou outra porta se necessário
$cfg['Servers'][$i]['socket'] = '';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['extension'] = 'mysqli';
$cfg['Servers'][$i]['auth_type'] = 'cookie';
