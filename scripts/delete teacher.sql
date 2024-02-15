DELETE FROM user
WHERE user.id = (
SELECT id FROM user
WHERE user.username = 'Dario');