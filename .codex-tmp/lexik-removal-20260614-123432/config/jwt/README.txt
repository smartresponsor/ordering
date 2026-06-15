Generate keys:
openssl genrsa -out private.pem -aes256 4096
openssl rsa -pubout -in private.pem -out public.pem
Pass phrase must match lexik_jwt_authentication.pass_phrase
