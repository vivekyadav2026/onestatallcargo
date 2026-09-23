import os
import glob

base_path = r"c:\xampp\htdocs\onestatallcargo\resources\views"
blade_files = glob.glob(os.path.join(base_path, "**/*.blade.php"), recursive=True)

replacements = {
    "Testwise EdTech Center": "OneStall Logistics Center",
    "Testwise Console": "OneStall Cargo Console",
    "Testwise EdTech Platform": "OneStall Cargo Platform",
    "Testwise - MP Police Constable GD 2026 Batch & Exam Engine": "OneStall Cargo - End-to-End Logistics Solution",
    "Testwise Support": "OneStall Cargo Support",
    "Testwise Webbooks": "OneStall Cargo Logistics",
    "Testwise - India's Most Advanced Webbook Platform": "OneStall Cargo - End-to-End Logistics Engine",
    "Testwise": "OneStall Cargo",
    "MP Police Constable GD 2026 - New Pattern & Chapter Notes Live": "OneStall Cargo - Book your Shipments & Track with Video Proof",
    "MP Police Constable GD 2026 Batch Systems": "OneStall Cargo Logistics Systems",
    "MP Police GD 2026 course access": "OneStall Cargo logistics platform",
    "MP Police GD 2026 Admin": "OneStall Cargo Admin",
    "MP Police Exam Portal": "Logistics Platform",
    "MP Police GD Mock 1": "Hub Transit",
    "MP Police GD Mock 2": "Out for Delivery",
    "MP Police GD 2026": "OneStall Cargo",
    "MP Police": "Logistics Network",
    "support@testwise.in": "support@onestallcargo.com",
    "Webbooks, Chapter Notes & CBT Mock Tests": "API Integrations, Evidence Tracking & B2B/B2C Cargo",
    "continue your exam preparation on": "access your dashboard on",
}

for file_path in blade_files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original_content = content
    for old, new in replacements.items():
        content = content.replace(old, new)
        # Also replace lower/upper variants if needed
        content = content.replace(old.lower(), new)
        content = content.replace(old.upper(), new)

    if original_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)

print(f"Processed {len(blade_files)} blade files.")
