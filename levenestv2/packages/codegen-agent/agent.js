const fs = require('fs');

function generateCode(requirements, context) {
  const lang = (context && context.mainLanguage) ? context.mainLanguage.toLowerCase() : 'unknown';
  let code = '';
  if (lang === 'javascript') {
    code = `// Auto-generated code\nfunction main() {\n  console.log('Hello, World!');\n}`;
  } else if (lang === 'python') {
    code = `# Auto-generated code\ndef main():\n    print('Hello, World!')`;
  } else {
    code = '// Auto-generated code\n// Language not detected. Please specify.';
  }
  return { code, language: lang, requirements, context };
}

function readStdinJSON() {
  return new Promise((resolve, reject) => {
    let data = '';
    process.stdin.setEncoding('utf8');
    process.stdin.on('data', chunk => data += chunk);
    process.stdin.on('end', () => {
      try {
        resolve(JSON.parse(data));
      } catch (e) {
        reject(e);
      }
    });
  });
}

if (require.main === module) {
  readStdinJSON().then(input => {
    const requirements = input.requirements || 'No requirements specified.';
    const context = input.context || input;
    const output = generateCode(requirements, context);
    process.stdout.write(JSON.stringify(output, null, 2));
  }).catch(err => {
    process.stderr.write('Invalid input: ' + err.message);
    process.exit(1);
  });
}

module.exports = { generateCode };
