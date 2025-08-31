import pkg from 'whatsapp-web.js';
const { Client, LocalAuth, Location } = pkg;
import qrcode from 'qrcode-terminal';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

let client = new Client({
    authStrategy: new LocalAuth({
        clientId: "client-one",
        dataPath: path.resolve(__dirname, 'sessions')
    }),
    /* puppeteer: {
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
    headless: false // Modo no headless para facilitar el debug
} */
});

async function syncMessages() {
    // Antes de sincronizar, cambiamos Puppeteer a modo no headless
    console.log('Switching to non-headless mode for syncing messages...');

    // Detener el cliente actual para reconfigurarlo en modo no headless
    await client.destroy();

    // Re-creamos el cliente en modo no headless
    client = new Client({
        authStrategy: new LocalAuth({
            clientId: "client-one",
            dataPath: path.resolve(__dirname, 'sessions')
        }),
        puppeteer: {
            args: ['--no-sandbox', '--disable-setuid-sandbox'], // Argumentos de Puppeteer
            headless: false, // Ahora en modo no headless (con interfaz visible)
        }
    });

    console.log('Opening WhatsApp Web to sync messages...');
    await client.initialize(); // Inicializa el cliente para abrir WhatsApp Web en modo visible

    // Espera 1 minuto (60,000 milisegundos)
    setTimeout(async () => {
        console.log('Closing WhatsApp Web...');
        await client.destroy(); // Cierra el cliente después de 1 minuto
    }, 60000);
}


// Función para enviar un mensaje de texto
async function sendMessage(phoneNumber, message) {
    try {
        const chatId = `${phoneNumber}@c.us`;
        const chat = await client.getChatById(chatId);
        await chat.sendMessage(message);
        console.log(`Message sent to ${phoneNumber}`);
        await new Promise(resolve => setTimeout(resolve, 1000));
        client.destroy(); // Cierra el cliente después de enviar el mensaje
    } catch (error) {
        console.error('Error sending message:', error);
        await new Promise(resolve => setTimeout(resolve, 1000));
        client.destroy(); // Cierra el cliente después de enviar el mensaje
    }
}

// Función para enviar una ubicación
async function sendLocation(phoneNumber, latitude, longitude, options = {}) {
    try {
        const chatId = `${phoneNumber}@c.us`;
        const chat = await client.getChatById(chatId);
        const location = new Location(latitude, longitude, options);
        await chat.sendMessage(location);
        console.log(`Location sent to ${phoneNumber}`);
        await new Promise(resolve => setTimeout(resolve, 1000));
        client.destroy(); // Cierra el cliente después de enviar el mensaje
    } catch (error) {
        console.error('Error sending location:', error);
        await new Promise(resolve => setTimeout(resolve, 1000));
        client.destroy(); // Cierra el cliente después de enviar el mensaje
    }
}

client.on('qr', (qr) => {
    qrcode.generate(qr, { small: true });
});

client.on('ready', async () => {
    console.log('Client is ready!');
    // Verificar si 'syncMessages' se pasa como argumento en la consola
    const [, , command] = process.argv;  // Recoger el comando desde la consola

    if (command === 'syncMessages') {
        // Si se pasa "syncMessages" como argumento, ejecuta la función
        await syncMessages();
    } else {
        console.log('No syncMessages command received.');
    }
    const [phoneNumber, message, lat, long] = process.argv.slice(2);

    if (client.pupPage && client.pupPage.isClosed()) {
        console.log('Puppeteer page is closed. Reinitializing client.');
        client.initialize(); // Reinitialize if needed
    }

    // Agregar un pequeño retraso para asegurar que todo esté listo
    setTimeout(async () => {
        try {
            if (message) {
                console.log(`Attempting to send message to ${phoneNumber}`);
                await sendMessage(phoneNumber, message);
            }
            if (lat && long) {
                console.log(`Attempting to send location to ${phoneNumber}`);
                await sendLocation(phoneNumber, parseFloat(lat), parseFloat(long), { title: 'My Location' });
            }
        } catch (error) {
            console.error('Error occurred:', error);
        }
    }, 3000); // Espera 5 segundos antes de enviar el mensaje o ubicación
});

client.on('authenticated', () => {
    console.log('Client is authenticated');
});

client.on('auth_failure', () => {
    console.log('Authentication failed');
});

client.initialize();

export { client, sendMessage, sendLocation };
