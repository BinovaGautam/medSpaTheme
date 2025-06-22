const fs = require('fs');
const path = require('path');

function extractContext(projectPath) {
  let context = {
    techStack: [],
    mainLanguage: null,
    dependencies: []
  };

  // Check for package.json (Node.js)
  const pkgPath = path.join(projectPath, 'package.json');
  if (fs.existsSync(pkgPath)) {
    const pkg = JSON.parse(fs.readFileSync(pkgPath, 'utf8'));
    context.techStack.push('Node.js');
    context.mainLanguage = 'JavaScript';
    context.dependencies = Object.keys(pkg.dependencies || {});
    return context;
  }

  // Check for requirements.txt (Python)
  const reqPath = path.join(projectPath, 'requirements.txt');
  if (fs.existsSync(reqPath)) {
    const reqs = fs.readFileSync(reqPath, 'utf8').split('\n').filter(Boolean);
    context.techStack.push('Python');
    context.mainLanguage = 'Python';
    context.dependencies = reqs;
    return context;
  }

  // Fallback: unknown project type
  context.techStack.push('Unknown');
  context.mainLanguage = 'Unknown';
  return context;
}

// For CLI usage or orchestrator integration
if (require.main === module) {
  const projectPath = process.argv[2] || process.cwd();
  const context = extractContext(projectPath);
  console.log(JSON.stringify(context, null, 2));
}

module.exports = { extractContext };
