import client from './whatsapp.js';

// Lógica para enviar el mensaje aquí
const sendMessage = async (phoneNumber, message) => {
    try {
        const chatId = `${phoneNumber}@c.us`;
        console.log(`Sending message to ${chatId}`);
        await client.sendMessage(chatId, message);
        console.log('Message sent successfully');
    } catch (error) {
        console.error('Error sending message:', error);
    }
};


// Obtener argumentos de la línea de comandos
const [phoneNumber, message] = process.argv.slice(2);
sendMessage(phoneNumber, message);
