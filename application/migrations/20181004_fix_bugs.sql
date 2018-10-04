update user set avatar = '' WHERE avatar = 'media/avatar/user/user.png';
update user set avatar = REPLACE(avatar, 'media/avatar/user', 'media/avatar') WHERE avatar like  'media/avatar/user%';
