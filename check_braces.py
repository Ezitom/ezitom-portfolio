def check_braces(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        content = f.read()
    
    stack = []
    for i, char in enumerate(content):
        if char == '{':
            stack.append(i)
        elif char == '}':
            if not stack:
                print(f"STRAY '}}' found at index {i}")
                # Print context
                start = max(0, i - 20)
                end = min(len(content), i + 20)
                print(f"Context: ...{content[start:end]}...")
            else:
                stack.pop()
    
    if stack:
        for pos in stack:
            print(f"UNCLOSED '{{' found at index {pos}")
            start = max(0, pos - 20)
            end = min(len(content), pos + 20)
            print(f"Context: ...{content[start:end]}...")

print("Checking index.html:")
check_braces('index.html')
print("\nChecking projects.html:")
check_braces('projects.html')
